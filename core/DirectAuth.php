<?php
/**
 * DirectAuth — Handles authentication directly against Supabase PostgreSQL.
 * This bypasses the Rust API for auth operations, connecting to the real database.
 */
class DirectAuth {
    private static ?PDO $pdo = null;

    private static function getDB(): PDO {
        if (self::$pdo === null) {
            $dsn = "pgsql:host=aws-0-ap-southeast-1.pooler.supabase.com;port=6543;dbname=postgres;sslmode=require";
            $user = "postgres.newkzurvjqfdcmbsscet";
            $password = "Johnpaulgardocce@1289";
            
            self::$pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$pdo;
    }

    /**
     * Login a user by email/username and password
     */
    public static function login(string $identifier, string $password): array {
        try {
            $db = self::getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :id OR username = :id LIMIT 1");
            $stmt->execute([':id' => $identifier]);
            $user = $stmt->fetch();

            if (!$user) {
                return ['error' => 'Invalid credentials'];
            }

            // Check if banned
            if (isset($user['status']) && $user['status'] === 'Banned') {
                return ['error' => 'Account is banned. Please contact support.'];
            }

            // Verify password against stored hash
            if (!password_verify($password, $user['password_hash'])) {
                return ['error' => 'Invalid credentials'];
            }

            // Generate a simple JWT token
            $token = self::generateJWT($user['id'], $user['role']);

            // Remove password_hash from response
            unset($user['password_hash']);

            return [
                'token' => $token,
                'user' => $user
            ];
        } catch (\Exception $e) {
            error_log("DirectAuth login error: " . $e->getMessage());
            return ['error' => 'Authentication service error. Please try again.'];
        }
    }

    /**
     * Register a new user
     */
    public static function register(string $email, string $password, string $role, string $firstName, string $lastName): array {
        try {
            $db = self::getDB();

            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                return ['error' => 'This email is already registered.'];
            }

            // Hash password with Argon2id (compatible with Rust backend)
            $hash = password_hash($password, PASSWORD_ARGON2ID, [
                'memory_cost' => 19456,
                'time_cost' => 2,
                'threads' => 1
            ]);

            // Insert user
            $stmt = $db->prepare("INSERT INTO users (email, password_hash, role, first_name, last_name) VALUES (:email, :hash, :role, :fn, :ln)");
            $stmt->execute([
                ':email' => $email,
                ':hash' => $hash,
                ':role' => $role,
                ':fn' => $firstName,
                ':ln' => $lastName,
            ]);

            return ['status' => 'success', 'message' => 'Registration successful'];
        } catch (\PDOException $e) {
            error_log("DirectAuth register error: " . $e->getMessage());
            $msg = $e->getMessage();
            if (strpos($msg, 'duplicate key') !== false || strpos($msg, 'unique constraint') !== false) {
                return ['error' => 'This email is already registered.'];
            }
            return ['error' => 'Registration failed DB Error: ' . $e->getMessage()];
        }
    }

    /**
     * Reset a user's password
     */
    public static function resetPassword(string $email, string $newPassword): array {
        try {
            $db = self::getDB();
            
            $hash = password_hash($newPassword, PASSWORD_ARGON2ID, [
                'memory_cost' => 19456,
                'time_cost' => 2,
                'threads' => 1
            ]);

            $stmt = $db->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
            $stmt->execute([':hash' => $hash, ':email' => $email]);

            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Password reset successful'];
            }
            return ['error' => 'User not found'];
        } catch (\Exception $e) {
            error_log("DirectAuth reset error: " . $e->getMessage());
            return ['error' => 'Password reset failed.'];
        }
    }

    /**
     * Get all users (for admin panel)
     */
    public static function getUsers(): array {
        try {
            $db = self::getDB();
            $stmt = $db->query("SELECT id, email, username, first_name, last_name, role, status, created_at, updated_at FROM users ORDER BY created_at DESC");
            return ['status' => 'success', 'users' => $stmt->fetchAll()];
        } catch (\Exception $e) {
            error_log("DirectAuth getUsers error: " . $e->getMessage());
            return ['error' => 'Failed to fetch users'];
        }
    }

    /**
     * Get platform stats
     */
    public static function getStats(): array {
        try {
            $db = self::getDB();
            $stmt = $db->query("SELECT count(*) as cnt FROM users");
            $count = $stmt->fetch()['cnt'];
            return ['users' => (int)$count, 'projects' => 0, 'agents' => 15, 'uptime' => '99.9%'];
        } catch (\Exception $e) {
            return ['users' => 0, 'projects' => 0, 'agents' => 15, 'uptime' => '99.9%'];
        }
    }

    /**
     * Ban/unban/delete user
     */
    public static function banUser(string $id): array {
        try {
            $db = self::getDB();
            $stmt = $db->prepare("UPDATE users SET status = 'Banned' WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['error' => 'Failed to ban user'];
        }
    }

    public static function unbanUser(string $id): array {
        try {
            $db = self::getDB();
            $stmt = $db->prepare("UPDATE users SET status = 'Active' WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['error' => 'Failed to unban user'];
        }
    }

    public static function deleteUser(string $id): array {
        try {
            $db = self::getDB();
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['error' => 'Failed to delete user'];
        }
    }

    /**
     * Generate a JWT token (HS256)
     */
    private static function generateJWT(string $userId, string $role): string {
        $secret = 'research_ai_jwt_secret_key_2026_ultra_secure';
        
        $header = self::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = self::base64UrlEncode(json_encode([
            'sub' => $userId,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + (24 * 3600) // 24 hours
        ]));
        
        $signature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", $secret, true)
        );
        
        return "$header.$payload.$signature";
    }

    private static function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
