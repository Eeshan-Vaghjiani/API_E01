<?php
// For Composer
require '../vendor/autoload.php';
// For manual installation
require '../vendor\phpmailer\phpmailer\src\PHPMailer.php';
require '../vendor\phpmailer\phpmailer\src\SMTP.php';
require '../vendor\phpmailer\phpmailer\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // Create a new PHPMailer instance

try {
    //Server settings
    $mail->isSMTP();                                           // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';                  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                // Enable SMTP authentication
    $mail->Username   = 'evaghjiani04@gmail.com';           // SMTP username
    $mail->Password   = 'lqsl iyrh krra potu';               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                 // TCP port to connect to

    //Recipients
    $mail->setFrom('evaghjiani04@gmail.com', 'Eeshan'); // Sender's email and name
    $mail->addAddress('evaghjiani@gmail.com', 'eeshan'); // Add a recipient

    // Content
    $mail->isHTML(true);                                     // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();                                          // Send the email
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>