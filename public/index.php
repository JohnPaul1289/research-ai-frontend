<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/ApiClient.php';
require_once __DIR__ . '/../core/DirectAuth.php';
require_once __DIR__ . '/../core/Mailer.php';

// Initialize session handling
Session::start();

$router = new Router();

// ─── Auto-Login via Remember Me ─────────────────────────────────────────────
if (!Session::get('jwt_token')) {
    $jwt = Session::getRememberCookie();
    if ($jwt) {
        Session::set('jwt_token', $jwt);
    }
}

$maintenance_file = __DIR__ . '/../core/maintenance.flag';

// ─── Middleware: CSRF Protection ────────────────────────────────────────────
$router->addMiddleware(function($uri) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (strpos($uri, '/api/') === 0) return; // Skip CSRF for JS API calls
        $token = $_POST['csrf_token'] ?? null;
        if (!Session::validateCsrfToken($token)) {
            // Provide a seamless UX instead of a white screen
            $redirectMap = [
                '/login' => '/login',
                '/register' => '/register',
                '/forgot-password' => '/forgot-password',
                '/reset-password' => '/reset-password',
                '/verify-email' => '/verify-email'
            ];
            
            if (isset($redirectMap[$uri])) {
                header('Location: ' . $redirectMap[$uri]);
                exit;
            }
            
            http_response_code(403);
            die("CSRF Token Validation Failed. Please go back, refresh the page, and try again.");
        }
    }
});

// ─── Middleware: Maintenance Mode ─────────────────────────────────────────────
$router->addMiddleware(function($uri) use ($maintenance_file) {
    // Check if system is in maintenance mode
    if (file_exists($maintenance_file)) {
        // Exclude admin API routes so they can toggle it off
        if (strpos($uri, '/api/admin/maintenance') === 0) {
            return;
        }
        
        // Let administrators bypass maintenance mode
        $user = Session::get('user');
        if ($user && isset($user['role']) && $user['role'] === 'Administrator') {
            return;
        }

        // If not admin and not trying to access the maintenance page, redirect
        if ($uri !== '/maintenance' && $uri !== '/logout' && $uri !== '/login') {
            header('Location: /maintenance');
            exit;
        }
    } else {
        // If system is online but user tries to visit maintenance page, redirect to home
        if ($uri === '/maintenance') {
            header('Location: /');
            exit;
        }
    }
});

// ─── Public Routes ───────────────────────────────────────────────────────────

$router->get('/', function() {
    $stats = DirectAuth::getStats();
    require_once __DIR__ . '/../views/home.php';
});

$router->get('/maintenance', function() {
    require_once __DIR__ . '/../views/maintenance.php';
});

$router->get('/admin/settings', function() {
    require_once __DIR__ . '/../admin/settings.php';
});

$router->post('/admin/ban', function() {
    if (!Session::get('jwt_token')) { header("Location: /login"); exit; }
    $id = $_POST['id'] ?? '';
    if ($id) DirectAuth::banUser($id);
    header("Location: /admin/users");
});

$router->post('/admin/unban', function() {
    if (!Session::get('jwt_token')) { header("Location: /login"); exit; }
    $id = $_POST['id'] ?? '';
    if ($id) DirectAuth::unbanUser($id);
    header("Location: /admin/users");
});

$router->post('/admin/delete', function() {
    if (!Session::get('jwt_token')) { header("Location: /login"); exit; }
    $id = $_POST['id'] ?? '';
    if ($id) DirectAuth::deleteUser($id);
    header("Location: /admin/users");
});

$router->get('/login', function() {
    require_once __DIR__ . '/../views/login.php';
});

$router->post('/login', function() {
    // Brute force protection
    $attempts = Session::get('login_attempts') ?? 0;
    $lockout = Session::get('lockout_time') ?? 0;
    if ($lockout > time()) {
        $error = "Account locked due to too many failed attempts. Try again in " . ceil(($lockout - time()) / 60) . " minutes.";
        require_once __DIR__ . '/../views/login.php';
        return;
    }

    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']);

    if (empty($identifier) || empty($password)) {
        $error = "Please enter both email and password.";
        require_once __DIR__ . '/../views/login.php';
        return;
    }

    $response = DirectAuth::login($identifier, $password);

    if ($response && isset($response['token'])) {
        Session::set('login_attempts', 0); // Reset attempts
        Session::regenerate(); // Prevent session fixation

        if ($rememberMe) {
            Session::setRememberCookie($response['token']);
        }
        Session::set('jwt_token', $response['token']);
        Session::set('user', $response['user']);
        header('Location: /dashboard');
        exit;
    } else {
        $attempts++;
        Session::set('login_attempts', $attempts);
        if ($attempts >= 5) {
            Session::set('lockout_time', time() + (15 * 60));
            $error = "Too many failed attempts. Account locked for 15 minutes.";
        } elseif ($response && isset($response['error'])) {
            // Surface specific API errors (e.g. "Account is banned")
            $error = $response['error'];
        } else {
            $error = "Invalid email or password.";
        }
        require_once __DIR__ . '/../views/login.php';
    }
});

// ─── Registration Flow (Step 1: Validate → Send Code) ───────────────────────

$router->get('/register', function() {
    require_once __DIR__ . '/../views/register.php';
});

$router->post('/register', function() {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'Student';

    // Strict Backend Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
        require_once __DIR__ . '/../views/register.php';
        return;
    }
    if (!preg_match("/^[a-zA-Z\s\-]{2,50}$/", $first_name) || !preg_match("/^[a-zA-Z\s\-]{2,50}$/", $last_name)) {
        $error = "Names must contain only letters and be 2-50 characters long.";
        require_once __DIR__ . '/../views/register.php';
        return;
    }
    $email = filter_var(strtolower($email), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
        require_once __DIR__ . '/../views/register.php';
        return;
    }
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
        require_once __DIR__ . '/../views/register.php';
        return;
    }
    // Strong password policy
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{12,}$/", $password)) {
        $error = "Password must be at least 12 characters, with uppercase, lowercase, number, and special character.";
        require_once __DIR__ . '/../views/register.php';
        return;
    }
    $validRoles = ['Student', 'Faculty', 'Research Adviser'];
    if (!in_array($role, $validRoles)) {
        $error = "Invalid role selected.";
        require_once __DIR__ . '/../views/register.php';
        return;
    }

    // Store pending registration in session
    $code = Mailer::generateCode();
    Session::set('pending_registration', [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => $password,
        'role' => $role,
    ]);
    Session::set('pending_email', $email);
    Session::set('verification_code', $code);
    Session::set('code_expires', time() + 600); // 10 min expiry

    // Send verification email
    $sent = Mailer::sendVerificationCode($email, $first_name, $code);
    if (!$sent) {
        // If email fails, still redirect but show a warning (code is in session for testing)
        error_log("Email sending failed — code is: {$code}");
    }

    header('Location: /verify-email');
    exit;
});

// ─── Registration Flow (Step 2: Verify Code → Create Account) ───────────────

$router->get('/verify-email', function() {
    if (!Session::get('pending_registration')) {
        header('Location: /register');
        exit;
    }
    require_once __DIR__ . '/../views/verify-email.php';
});

$router->post('/verify-email', function() {
    $pending = Session::get('pending_registration');
    if (!$pending) {
        header('Location: /register');
        exit;
    }

    $submittedCode = trim($_POST['code'] ?? '');
    $storedCode = Session::get('verification_code');
    $expires = Session::get('code_expires');

    if (time() > $expires) {
        $error = "Code has expired. Please request a new one.";
        require_once __DIR__ . '/../views/verify-email.php';
        return;
    }

    if ($submittedCode !== $storedCode) {
        $error = "Invalid verification code. Please try again.";
        require_once __DIR__ . '/../views/verify-email.php';
        return;
    }

    // Code verified! Register directly to database
    $response = DirectAuth::register(
        $pending['email'],
        $pending['password'],
        $pending['role'],
        $pending['first_name'],
        $pending['last_name']
    );

    // Clear pending data
    Session::remove('pending_registration');
    Session::remove('pending_email');
    Session::remove('verification_code');
    Session::remove('code_expires');

    if ($response && isset($response['status']) && $response['status'] === 'success') {
        $success = "Account verified and created! Please sign in.";
        require_once __DIR__ . '/../views/login.php';
    } else {
        $err = $response['error'] ?? '';
        if (empty($err)) {
            $error = "Registration failed. The server may be temporarily unavailable. Please try again.";
        } elseif (stripos($err, 'duplicate key') !== false || stripos($err, 'unique constraint') !== false || stripos($err, 'already registered') !== false) {
            $error = "This email address is already registered. Please sign in instead.";
        } elseif (stripos($err, 'Registration failed:') !== false) {
            // Strip the "Registration failed:" prefix to avoid "Registration failed: Registration failed"
            $cleanErr = trim(str_ireplace('Registration failed:', '', $err));
            $error = $cleanErr ?: "Registration failed. Please try again later.";
        } else {
            $error = $err;
        }
        require_once __DIR__ . '/../views/register.php';
    }
});

$router->post('/resend-code', function() {
    $pending = Session::get('pending_registration');
    if (!$pending) {
        header('Location: /register');
        exit;
    }

    $code = Mailer::generateCode();
    Session::set('verification_code', $code);
    Session::set('code_expires', time() + 600);

    Mailer::sendVerificationCode($pending['email'], $pending['first_name'], $code);

    $resent = true;
    require_once __DIR__ . '/../views/verify-email.php';
});

// ─── Forgot Password Flow ───────────────────────────────────────────────────

$router->get('/forgot-password', function() {
    require_once __DIR__ . '/../views/forgot-password.php';
});

$router->post('/forgot-password', function() {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = "Please enter your email address.";
        require_once __DIR__ . '/../views/forgot-password.php';
        return;
    }

    // Generate code and store in session
    $code = Mailer::generateCode();
    Session::set('reset_email', $email);
    Session::set('reset_code', $code);
    Session::set('reset_code_expires', time() + 600);

    // Log code to PHP dev server (visible in terminal)
    error_log("🔑 Password reset code for {$email}: {$code}");

    // Send reset email
    Mailer::sendPasswordResetCode($email, $code);

    header('Location: /reset-password');
    exit;
});

$router->get('/reset-password', function() {
    if (!Session::get('reset_email')) {
        header('Location: /forgot-password');
        exit;
    }
    require_once __DIR__ . '/../views/reset-password.php';
});

$router->post('/reset-password', function() {
    $resetEmail = Session::get('reset_email');
    if (!$resetEmail) {
        header('Location: /forgot-password');
        exit;
    }

    $submittedCode = trim($_POST['code'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $storedCode = Session::get('reset_code');
    $expires = Session::get('reset_code_expires');

    if (time() > $expires) {
        $error = "Reset code has expired. Please request a new one.";
        require_once __DIR__ . '/../views/reset-password.php';
        return;
    }
    if ($submittedCode !== $storedCode) {
        $error = "Invalid reset code.";
        require_once __DIR__ . '/../views/reset-password.php';
        return;
    }
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
        require_once __DIR__ . '/../views/reset-password.php';
        return;
    }
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
        require_once __DIR__ . '/../views/reset-password.php';
        return;
    }

    // Reset password directly in database
    $response = DirectAuth::resetPassword($resetEmail, $password);

    // Clear reset session data
    Session::remove('reset_email');
    Session::remove('reset_code');
    Session::remove('reset_code_expires');

    if ($response && isset($response['status']) && $response['status'] === 'success') {
        $success = "Password reset successfully! Please sign in with your new password.";
        require_once __DIR__ . '/../views/login.php';
    } else {
        $error = $response['error'] ?? "Password reset failed.";
        require_once __DIR__ . '/../views/forgot-password.php';
    }
});

// ─── Authenticated Routes ────────────────────────────────────────────────────

$router->get('/chat', function() {
    if (!Session::get('jwt_token')) { header('Location: /login'); exit; }
    require_once __DIR__ . '/../views/chat.php';
});

$router->post('/api/chat', function() {
    if (!Session::get('jwt_token')) { http_response_code(401); echo json_encode(['error' => 'Unauthorized']); exit; }
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['message'])) { http_response_code(400); echo json_encode(['error' => 'No message provided']); exit; }
    $agent = $data['agent'] ?? 'Research Assistant';
    $msg = htmlspecialchars($data['message']);

    $session_id = $data['session_id'] ?? null;

    // Attempt to call the real Rust Engine first!
    $apiResponse = ApiClient::post('/api/chat', [
        'message' => $data['message'],
        'agent' => $agent,
        'user_id' => Session::get('user_id'),
        'session_id' => $session_id
    ]);

    if ($apiResponse && !isset($apiResponse['error'])) {
        // Real Engine Responded!
        $response = [
            'reply' => $apiResponse['reply'] ?? $apiResponse['response'] ?? "Error parsing engine response.",
            'agent' => $agent,
            'session_id' => $apiResponse['session_id'] ?? null
        ];
    } else {
        // Strict failure if engine is down
        $response = [
            'reply' => "Connection to the proprietary AI engine failed. Please try again later.",
            'agent' => $agent
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
});

$router->get('/dashboard', function() {
    if (!Session::get('jwt_token')) { header('Location: /login'); exit; }
    $user = Session::get('user');
    if ($user['role'] === 'Administrator') {
        require_once __DIR__ . '/../admin/dashboard.php';
    } else {
        require_once __DIR__ . '/../student/dashboard.php';
    }
});

// -- Student Routes --
$router->get('/projects', function() {
    if (!Session::get('jwt_token')) { header('Location: /login'); exit; }
    require_once __DIR__ . '/../student/projects.php';
});

$router->get('/repository', function() {
    if (!Session::get('jwt_token')) { header('Location: /login'); exit; }
    require_once __DIR__ . '/../student/repository.php';
});

$router->get('/settings', function() {
    if (!Session::get('jwt_token')) { header('Location: /login'); exit; }
    require_once __DIR__ . '/../student/settings.php';
});

// -- Admin Routes --
$router->get('/admin/users', function() {
    if (!Session::get('jwt_token') || Session::get('user')['role'] !== 'Administrator') { header('Location: /dashboard'); exit; }
    require_once __DIR__ . '/../admin/users.php';
});

$router->get('/admin/engine', function() {
    if (!Session::get('jwt_token') || Session::get('user')['role'] !== 'Administrator') { header('Location: /dashboard'); exit; }
    require_once __DIR__ . '/../admin/engine.php';
});

$router->get('/admin/agents', function() {
    if (!Session::get('jwt_token') || Session::get('user')['role'] !== 'Administrator') { header('Location: /dashboard'); exit; }
    require_once __DIR__ . '/../admin/agents.php';
});

$router->get('/admin/projects', function() {
    if (!Session::get('jwt_token') || Session::get('user')['role'] !== 'Administrator') { header('Location: /dashboard'); exit; }
    require_once __DIR__ . '/../admin/projects.php';
});

$router->get('/admin/settings', function() {
    if (!Session::get('jwt_token') || Session::get('user')['role'] !== 'Administrator') { header('Location: /dashboard'); exit; }
    require_once __DIR__ . '/../admin/settings.php';
});

$router->get('/logout', function() {
    Session::destroy();
    header('Location: /login');
    exit;
});

// -- Admin API Routes --
$router->get('/api/admin/maintenance/status', function() use ($maintenance_file) {
    if (!Session::get('jwt_token') || Session::get('user')['role'] !== 'Administrator') { 
        http_response_code(403); 
        echo json_encode(['error' => 'Forbidden']); 
        exit; 
    }
    
    header('Content-Type: application/json');
    echo json_encode(['enabled' => file_exists($maintenance_file)]);
});

$router->post('/api/admin/maintenance', function() use ($maintenance_file) {
    if (!Session::get('jwt_token') || Session::get('user')['role'] !== 'Administrator') { 
        http_response_code(403); 
        echo json_encode(['error' => 'Forbidden']); 
        exit; 
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $enabled = $data['enabled'] ?? false;

    if ($enabled) {
        file_put_contents($maintenance_file, '1');
    } else {
        if (file_exists($maintenance_file)) {
            unlink($maintenance_file);
        }
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'enabled' => $enabled]);
});

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
