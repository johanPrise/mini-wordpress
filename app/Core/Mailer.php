<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Config\Mail;

class Mailer{
    public static function send($to, $subject, $body){
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Host = Mail::MAIL_HOST;
            $mail->Username = Mail::MAIL_USER;
            $mail->Password = Mail::MAIL_PASS;
            $mail->Port = Mail::MAIL_PORT;
            $mail->setFrom(Mail::MAIL_FROM);
            $mail->addAddress($to);
            $mail->Subject = $subject;
        $mail->Body = $body;

        // 3. Envoyer le message
        return $mail->send();


    } catch (Exception $e) {
        return false;
    }
  }
}