<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    public static function sendActivationMail(string $email, string $token)
    {
        $mail = new PHPMailer(true);

        try {
            $activationLink =
                "http://localhost:8080/activate?email=" . urlencode($email)
                . "&token=" . urlencode($token);

            $mail->isSMTP();
            $mail->Host = "mailpit";
            $mail->Port = 1025;
            $mail->SMTPAuth = false;

            $mail->setFrom("no-reply@example.com", "Mini WP");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Activez votre compte";
            $mail->Body = "
                <p>Merci pour votre inscription.</p>
                <p>Cliquez ici pour activer votre compte :</p>
                <a href='$activationLink'>$activationLink</a>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("MAIL ERROR: " . $e->getMessage());
        }
    }
}