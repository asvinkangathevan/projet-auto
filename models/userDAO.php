<?php

require_once __DIR__ . "/../models/UserDAO.php";
require_once __DIR__ . "/../models/User.php";

class RegisterController
{
    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $email = $_POST["email"];
            $motDePasse = password_hash($_POST["motDePasse"], PASSWORD_DEFAULT);

            // Vérifier si l'email existe déjà
            if ($this->userDAO->findByEmail($email)) {
                echo "Email déjà utilisé.";
                return;
            }

            // Créer l’objet User
            $user = new User(null, $nom, $prenom, $email, $motDePasse);

            // Enregistrer
            if ($this->userDAO->create($user)) {
                echo "Inscription réussie !";
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }
    }
}
