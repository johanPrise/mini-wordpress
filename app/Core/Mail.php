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

    public static function sendResetPasswordMail(string $email, string $token)
    {
        $mail = new PHPMailer(true);

        try {
            $resetLink =
                "http://localhost:8080/reset-password?email=" . urlencode($email)
                . "&token=" . urlencode($token);

            $mail->isSMTP();
            $mail->Host = "mailpit";
            $mail->Port = 1025;
            $mail->SMTPAuth = false;

            $mail->setFrom("no-reply@example.com", "Mini WP");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Réinitialisation de votre mot de passe";
            $mail->Body = "
                <p>Vous avez demandé une réinitialisation de mot de passe !</p>
                <p>Cliquez sur le lien ci-dessous pour définir un nouveau mot de passe de votre compte:</p>
                <a href='$resetLink'>$resetLink</a>
            ";

            $mail->send();

        } catch (Exception $e) {
            error_log("MAIL ERROR (reset password): " . $e->getMessage());
        }
    }
}