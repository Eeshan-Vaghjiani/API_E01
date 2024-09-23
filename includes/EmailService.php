<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure PHPMailer is included

class EmailService {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'evaghjiani04@gmail.com';
        $this->mail->Password = 'lqsl iyrh krra potu'; // Use an app-specific password for better security
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
    }

    // Send registration email
    public function sendRegistrationEmail($email, $username) {
        try {
            $this->mail->setFrom('evaghjiani04@gmail.com', 'Eeshan');
            $this->mail->addAddress($email, $username);
            $this->mail->Subject = 'Welcome to Our Website';

            // HTML Body for Registration Email
            $this->mail->Body = "
            <html>
            <head>
                <style>
                    .email-body {
                        font-family: Arial, sans-serif;
                        color: #black;
                        padding: 20px;
                        border-radius: 10px;
                    }
                    h2, p {
                        color: #black;
                    }
                </style>
            </head>
            <body>
                <div class='email-body'>
                    <h2>Welcome, $username!</h2>
                    <p>We are thrilled to have you on board! Thank you for registering with us.</p>
                    <p>At Vaghjiani Innovations, we strive to provide the best experience for our users.</p>
                    <p>Here are a few things you can do to get started:</p>
                    <ul>
                        <li>Explore your dashboard</li>
                        <li>Customize your profile</li>
                        <li>Reach out to our support team if you need any assistance</li>
                    </ul>
                    <p>We are excited to help you achieve your goals!</p>
                    <p>Best regards,<br><strong>Eeshan Vaghjiani</strong><br>ICSE Internet Application Programming Project<br>166981<br>+254 704 861 135</p>
                </div>
            </body>
            </html>";

            $this->mail->isHTML(true); // Enable HTML content
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false; // Log the error if needed
        }
    }

    // Send 2FA code email
    public function send2FACode($email, $username, $code) {
        try {
            $this->mail->setFrom('evaghjiani04@gmail.com', 'Eeshan');
            $this->mail->addAddress($email, $username);
            $this->mail->Subject = 'Your 2FA Code';

            // HTML Body for 2FA Email
            $this->mail->Body = "
            <html>
            <head>
                <style>
                    .email-body {
                        font-family: Arial, sans-serif;
                        color: black;
                        padding: 20px;
                        border-radius: 10px;
                    }
                    h2, p {
                        color: black;
                    }
                </style>
            </head>
            <body>
                <div class='email-body'>
                    <h2>Dear $username,</h2>
                    <p>For your security, we have enabled two-factor authentication (2FA) for your account.</p>
                    <p>Your verification code is: <strong style='font-size: 24px;'>$code</strong></p>
                    <p>Please enter this code in the required field to complete your login process. This code is valid for a short period of time for your security.</p>
                    <p>If you did not request this code, please ignore this message.</p>
                    <p>Thank you for being a part of Vaghjiani Innovations!</p>
                    <p>Best regards,<br> <strong>Eeshan Vaghjiani</strong><br>ICSE Internet Application Programming Project<br>166981<br>+254 704 861 135</p>
                </div>
            </body>
            </html>";

            $this->mail->isHTML(true); // Enable HTML content
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false; // Log the error if needed
        }
    }
}
