<?php

class Session {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            // Enforce secure cookie params to prevent XSS and CSRF leakage
            session_set_cookie_params([
                'lifetime' => 0, // Session cookies expire when browser closes
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']), // HTTPS only if available
                'httponly' => true, // Prevent JS access to session cookie
                'samesite' => 'Lax' // CSRF protection
            ]);
            
            ini_set('session.use_strict_mode', '1');
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    public static function has(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    public static function regenerate(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function destroy(): void {
        session_unset();
        session_destroy();
        // Clear the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Also remove the Remember Me token if it exists
        self::removeRememberCookie();
    }

    // ─── CSRF Protection ────────────────────────────────────────────────────────
    
    public static function generateCsrfToken(): string {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }

    public static function validateCsrfToken(?string $token): bool {
        if (!$token || !self::has('csrf_token')) return false;
        return hash_equals(self::get('csrf_token'), $token);
    }

    // ─── Secure Remember Me (JWT Cookie Strategy) ───────────────────────────────

    /**
     * Since the backend is a stateless Rust API that provides a secure JWT, 
     * the most secure "Remember Me" implementation is storing the JWT in an 
     * HttpOnly, Secure cookie. This inherently prevents database state issues 
     * while completely securing the token from XSS attacks.
     */
    public static function setRememberCookie(string $jwtToken): void {
        $expires = time() + (30 * 24 * 3600); // 30 days
        setcookie('remember_jwt', $jwtToken, [
            'expires' => $expires,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    public static function getRememberCookie(): ?string {
        return $_COOKIE['remember_jwt'] ?? null;
    }

    public static function removeRememberCookie(): void {
        if (isset($_COOKIE['remember_jwt'])) {
            setcookie('remember_jwt', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            unset($_COOKIE['remember_jwt']);
        }
    }
}
