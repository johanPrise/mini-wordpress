<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Mail;
use App\Core\Session;

class AuthController extends Controller
{
    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $firstname = trim($_POST["firstname"]);
            $lastname = trim($_POST["lastname"]);
            $email = strtolower(trim($_POST["email"]));
            $password = trim($_POST["password"]);

            // Verif doublon email
            if (User::findByEmail($email)) {
                return $this->render("auth/register", ["error" => "Email déjà utilisé."]);
            }

            $token = bin2hex(random_bytes(32));

            User::create([
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "token" => $token
            ]);

            Mail::sendActivationMail($email, $token);

            return $this->render("auth/register", [
                "success" => "Votre compte a été créé. Vérifiez vos emails pour l'activer."
            ]);
        }

        return $this->render("auth/register");
    }

    public function activate()
    {
        $email = $_GET["email"] ?? null;
        $token = $_GET["token"] ?? null;

        if (!$email || !$token) {
            echo "Lien d'activation invalide.";
            return;
        }

        $rows = User::activate($email, $token);  //update users et set is_active = true

        if ($rows > 0) {  //Si 1 ligne modifiée = activation OK
            echo "<h1>Votre compte est activé !</h1>";
            echo "<a href='/login'>Se connecter</a>";
        } else {
            echo "<h1>L’activation a échoué.</h1>";
        }
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = strtolower(trim($_POST["email"]));
            $password = $_POST["password"];

            $user = User::findByEmail($email);

            if (!$user) {
                return $this->render("auth/login", ["error" => "Utilisateur non trouvé"]);
            }
            // echo "<pre>";
            // var_dump($user);
            // echo "</pre>";
            // exit;

            if (!$user["is_active"]) {
                return $this->render("auth/login", ["error" => "Votre compte n'est pas activé."]);
            }

            if (!password_verify($password, $user["password"])) {
                return $this->render("auth/login", ["error" => "Mot de passe incorrect"]);
            }

            Session::set("user", $user);
            header("Location: /");
            exit;
        }

        return $this->render("auth/login");
    }

    public function logout()
    {
        Session::destroy();
        header("Location: /");
        exit;
    }

    public function forgotPassword()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $email = strtolower(trim($_POST["email"]));
            $user = User::findByEmail($email);

            if (!$user) {
                return $this->render("auth/forgot-password", [
                    "error" => "Aucun compte trouvé pour cet email."
                ]);
            }

            //generation de token de reinit
            $token = bin2hex(random_bytes(32));

            // MODIFIED: Pass ID instead of email
            User::setResetToken($user['id'], $token);

            Mail::sendResetPasswordMail($email, $token);

            return $this->render("auth/forgot-password", [
                "success" => "Un email vous a été envoyé pour réinitialiser votre mot de passe."
            ]);
        }

        return $this->render("auth/forgot-password");
    }

    public function resetPassword()
    {
        $email = $_GET["email"] ?? null;
        $token = $_GET["token"] ?? null;

        if (!$email || !$token) {
            echo "Lien invalide.";
            return;
        }

        // Formulaire envoyé
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $password = trim($_POST["password"]);

            // MODIFIED: Find user by token first
            $user = User::findByResetToken($token);

            // Verify user exists and email matches (optional extra security but good)
            if ($user && $user['email'] === $email) {
                // Update password using ID
                User::resetPassword($user['id'], password_hash($password, PASSWORD_DEFAULT));
                
                echo "<h2>Votre mot de passe a été changé !</h2>";
                echo "<a href='/login'>Se connecter</a>";
                return;
            } else {
                 return $this->render("auth/reset-password", [
                    "error" => "Lien de réinitialisation invalide ou expiré.",
                    "email" => $email,
                    "token" => $token
                ]);
            }
        }

        //affichage du form
        return $this->render("auth/reset-password", [
            "email" => $email,
            "token" => $token
        ]);
    }

}
