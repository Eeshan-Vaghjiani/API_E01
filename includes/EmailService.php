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
            $this->mail->Body    = "
Dear $username,

We are thrilled to have you on board! Thank you for registering with us. At Vaghjiani Innovations, we strive to provide the best experience for our users. 

Here are a few things you can do to get started:
1. Explore your dashboard.
2. Customize your profile.
3. Reach out to our support team if you need any assistance.

We are excited to help you achieve your goals!

Best regards,
Eeshan Vaghjiani ICSE Internet Application Proggraming Project
166981,
+254 704 861 135.

" . $username . '!';
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
            
            $this->mail->Body    = "

Dear <h1> $username </h1>,<br>

For your security, we have enabled two-factor authentication (2FA) for your account.<br>

Your verification code is: <strong>$code</strong><br><br>

Please enter this code in the required field to complete your login process. This code is valid for a short period of time for your security.

If you did not request this code, please ignore this message.<br>

Thank you for being a part of Vaghjiani Innovations!<br>

Best regards,
Eeshan Vaghjiani ICSE Internet Application Proggraming Project
166981, 
+254 704 861 135.
";
        $this->mail->isHTML(true);
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false; // You can log the error message if needed
        }
    }
}
