<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private static ?array $config = null;

    private static function getConfig(): array {
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../config/mail.php';
        }
        return self::$config;
    }

    private static function createMailer(): ?PHPMailer {
        $config = self::getConfig();

        if (empty($config['smtp_username']) || empty($config['smtp_password'])) {
            error_log('Mailer: SMTP credentials not configured in config/mail.php');
            return null;
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtp_username'];
        $mail->Password   = $config['smtp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $config['smtp_port'];

        $mail->setFrom($config['smtp_username'], $config['from_name']);
        $mail->isHTML(true);

        return $mail;
    }

    /**
     * Generate a random 6-digit verification code
     */
    public static function generateCode(): string {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send email verification code for registration
     */
    public static function sendVerificationCode(string $toEmail, string $toName, string $code): bool {
        try {
            $mail = self::createMailer();
            if (!$mail) return false;

            $mail->addAddress($toEmail, $toName);
            $mail->Subject = 'Research AI — Verify Your Email';
            $mail->Body = self::buildEmailTemplate(
                'Email Verification',
                "Hello <strong>{$toName}</strong>,",
                "Your verification code is:",
                $code,
                'This code expires in 10 minutes. If you did not request this, please ignore this email.'
            );
            $mail->AltBody = "Your Research AI verification code is: {$code}";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error (Verification): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send password reset code
     */
    public static function sendPasswordResetCode(string $toEmail, string $code): bool {
        try {
            $mail = self::createMailer();
            if (!$mail) return false;

            $mail->addAddress($toEmail);
            $mail->Subject = 'Research AI — Password Reset';
            $mail->Body = self::buildEmailTemplate(
                'Password Reset',
                "Hello,",
                "Your password reset code is:",
                $code,
                'This code expires in 10 minutes. If you did not request a password reset, please ignore this email.'
            );
            $mail->AltBody = "Your Research AI password reset code is: {$code}";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error (Reset): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Build a premium HTML email template
     */
    private static function buildEmailTemplate(string $title, string $greeting, string $message, string $code, string $footer): string {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#030712;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#030712;padding:40px 20px;">
        <tr><td align="center">
            <table width="480" cellpadding="0" cellspacing="0" style="background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:16px;overflow:hidden;">
                <!-- Header -->
                <tr><td style="background:linear-gradient(135deg,#6366f1,#a855f7);padding:30px;text-align:center;">
                    <h1 style="color:#fff;margin:0;font-size:22px;font-weight:700;">🔬 Research AI</h1>
                    <p style="color:rgba(255,255,255,0.8);margin:8px 0 0;font-size:14px;">{$title}</p>
                </td></tr>
                <!-- Body -->
                <tr><td style="padding:40px 35px;">
                    <p style="color:#f8fafc;font-size:15px;margin:0 0 20px;">{$greeting}</p>
                    <p style="color:#94a3b8;font-size:14px;margin:0 0 20px;">{$message}</p>
                    <!-- Code Box -->
                    <div style="background:#1e293b;border:2px dashed #6366f1;border-radius:12px;padding:20px;text-align:center;margin:25px 0;">
                        <span style="font-size:36px;font-weight:800;letter-spacing:12px;color:#fff;font-family:monospace;">{$code}</span>
                    </div>
                    <p style="color:#64748b;font-size:13px;margin:0;">{$footer}</p>
                </td></tr>
                <!-- Footer -->
                <tr><td style="padding:20px 35px;border-top:1px solid rgba(255,255,255,0.05);">
                    <p style="color:#475569;font-size:12px;margin:0;text-align:center;">&copy; 2026 Research AI. All rights reserved.</p>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
HTML;
    }
}
