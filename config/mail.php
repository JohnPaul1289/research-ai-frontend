<?php
/**
 * Gmail SMTP Configuration for Research AI
 * 
 * INSTRUCTIONS:
 * 1. Go to https://myaccount.google.com/security
 * 2. Enable 2-Step Verification
 * 3. Go to App Passwords → Generate one for "Mail"
 * 4. Paste your Gmail and the 16-char App Password below
 */
return [
    'smtp_host'     => 'smtp.gmail.com',
    'smtp_port'     => 587,
    'smtp_username' => getenv('SMTP_USERNAME') ?: '',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: '',
    'from_name'     => 'Research AI',
];
