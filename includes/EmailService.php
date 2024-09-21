<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Make sure PHPMailer is included

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

    public function sendRegistrationEmail($email, $username) {
        try {
            $this->mail->setFrom('evaghjiani04@gmail.com', 'Eeshan');
            $this->mail->addAddress($email, $username);
            $this->mail->Subject = 'Welcome to Our Website';
            $this->mail->Body    = 'Thank you for registering, ' . $username . '!';
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false; // You can log the error message if needed
        }
    }

    public function send2FACode($email, $username, $code) {
        try {
            $this->mail->setFrom('evaghjiani04@gmail.com', 'Eeshan');
            $this->mail->addAddress($email, $username);
            $this->mail->Subject = 'Your 2FA Code';
            $this->mail->Body    = 'Your 2FA code is: ' . $code;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false; // You can log the error message if needed
        }
    }
}
