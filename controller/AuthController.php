<?php

require_once __DIR__ . "/../models/UserDAO.php";
require_once __DIR__ . "/../models/User.php";

class AuthController
{
    private $userDAO;

    public function __construct()
    {
        session_start();
        $this->userDAO = new UserDAO();
    }

    /* ====================== LOGIN ====================== */

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            // Chercher l'utilisateur
            $user = $this->userDAO->findByEmail($email);

            // Vérifier email + mot de passe
            if ($user && password_verify($password, $user->getMotDePasse())) {

                $_SESSION["user"] = [
                    "id" => $user->getId(),
                    "nom" => $user->getNom(),
                    "prenom" => $user->getPrenom(),
                    "email" => $user->getEmail()
                ];

                header("Location: /public/home");
                exit;
            }

            // Si erreur login
            $_SESSION["error"] = "Email ou mot de passe incorrect";
            header("Location: /public/login");
            exit;
        }
    }

    /* ====================== REGISTER ====================== */

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $nom = trim($_POST["nom"]);
            $prenom = trim($_POST["prenom"]);
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);

            // Vérifier si l'email existe déjà
            if ($this->userDAO->findByEmail($email)) {
                $_SESSION["error"] = "Cet email est déjà utilisé.";
                header("Location: /public/register");
                exit;
            }

            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Créer l'objet User
            $user = new User(null, $nom, $prenom, $email, $hashedPassword);

            // Enregistrer en base
            if ($this->userDAO->create($user)) {
                $_SESSION["success"] = "Inscription réussie ! Vous pouvez vous connecter.";
                header("Location: /public/login");
                exit;
            }

            $_SESSION["error"] = "Erreur lors de l'inscription.";
            header("Location: /public/register");
            exit;
        }
    }

    /* ====================== LOGOUT ====================== */

    public function logout()
    {
        session_destroy();
        header("Location: /public/login");
        exit;
    }
}
