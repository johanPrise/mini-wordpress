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

            $firstname = trim($_POST["firstname"] ?? '');
            $lastname = trim($_POST["lastname"] ?? '');
            $email = strtolower(trim($_POST["email"] ?? ''));
            $password = $_POST["password"] ?? '';
            $passwordConfirm = $_POST["password_confirm"] ?? '';

            // Validation des champs requis
            if (empty($firstname) || strlen($firstname) < 2) {
                return $this->render("auth/register", ["error" => "Le prénom doit contenir au moins 2 caractères."]);
            }

            if (empty($lastname) || strlen($lastname) < 2) {
                return $this->render("auth/register", ["error" => "Le nom doit contenir au moins 2 caractères."]);
            }

            // Validation email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->render("auth/register", ["error" => "Veuillez entrer une adresse email valide."]);
            }

            // Validation mot de passe (min 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre)
            if (strlen($password) < 8) {
                return $this->render("auth/register", ["error" => "Le mot de passe doit contenir au moins 8 caractères."]);
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                return $this->render("auth/register", ["error" => "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre."]);
            }

            // Confirmation mot de passe
            if ($password !== $passwordConfirm) {
                return $this->render("auth/register", ["error" => "Les mots de passe ne correspondent pas."]);
            }

            // Verif doublon email
            if (User::findByEmail($email)) {
                return $this->render("auth/register", ["error" => "Cet email est déjà utilisé."]);
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

            $email = strtolower(trim($_POST["email"] ?? ''));
            $password = $_POST["password"] ?? '';

            // Validation champs requis
            if (empty($email)) {
                return $this->render("auth/login", ["error" => "L'email est requis."]);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->render("auth/login", ["error" => "Veuillez entrer une adresse email valide."]);
            }

            if (empty($password)) {
                return $this->render("auth/login", ["error" => "Le mot de passe est requis."]);
            }

            $user = User::findByEmail($email);

            if (!$user) {
                return $this->render("auth/login", ["error" => "Identifiants incorrects."]);
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

            $password = $_POST["password"] ?? '';
            $passwordConfirm = $_POST["password_confirm"] ?? '';

            // Validation mot de passe
            if (strlen($password) < 8) {
                return $this->render("auth/reset-password", [
                    "error" => "Le mot de passe doit contenir au moins 8 caractères.",
                    "email" => $email,
                    "token" => $token
                ]);
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                return $this->render("auth/reset-password", [
                    "error" => "Le mot de passe doit contenir une majuscule, une minuscule et un chiffre.",
                    "email" => $email,
                    "token" => $token
                ]);
            }

            if ($password !== $passwordConfirm) {
                return $this->render("auth/reset-password", [
                    "error" => "Les mots de passe ne correspondent pas.",
                    "email" => $email,
                    "token" => $token
                ]);
            }

            // MODIFIED: Find user by token first
            $user = User::findByResetToken($token);

            // Verify user exists and email matches (optional extra security but good)
            if ($user && $user['email'] === $email) {
                // Update password using ID
                User::resetPassword($user['id'], password_hash($password, PASSWORD_DEFAULT));
                
                return $this->render("auth/login", [
                    "success" => "Votre mot de passe a été modifié. Vous pouvez maintenant vous connecter."
                ]);
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
