<?php
require_once __DIR__ . "/../models/UserDAO.php";
require_once __DIR__ . "/../models/User.php";
class RegisterController
{
    public function index()
    {
        require __DIR__ . "/../view/inscription.html";
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Méthode non autorisée.");
        }

        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $email = $_POST['email'] ?? null;
        $mot_de_passe = $_POST['mot_de_passe'] ?? null;

        if (!$nom || !$prenom || !$email || !$mot_de_passe) {
            die("Tous les champs sont obligatoires.");
        }

        // Hash du mot de passe
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

        // Création d’un utilisateur (OBJET)
        $user = new User(
            null,
            $nom,
            $prenom,
            $email,
            $mot_de_passe_hash
        );

        // DAO
        $dao = new UserDAO();
        $result = $dao->create($user);

        if ($result) {
            echo "Inscription réussie !";
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}
