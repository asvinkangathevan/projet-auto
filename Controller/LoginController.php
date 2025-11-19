require_once __DIR__ . '/../models/UserDAO.php';
require_once __DIR__ . '/../models/User.php';

class LoginController
{
    public function index()
    {
        require __DIR__ . '/../views/login.php';
    }

    public function login()
    {
        $dao = new UserDAO();

        $email = $_POST["email"];
        $password = $_POST["mot_de_passe"];

        $user = $dao->findByEmail($email);

        if ($user && password_verify($password, $user->getMotDePasse())) {
            header("Location: /BookingAuto/index.php?controller=home");
            exit;
        }

        echo "Erreur login";
    }
}
