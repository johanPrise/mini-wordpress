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

        $rows = User::activate($email, $token);

        if ($rows > 0) {
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
            header("Location: /admin/users");
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
}
