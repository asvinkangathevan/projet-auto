<?php

// Chargement automatique des contrôleurs
spl_autoload_register(function ($class) {
    $path = __DIR__ . "/controller/" . $class . ".php";
    if (file_exists($path)) {
        require $path;
    }
});

// Récupération du contrôleur et de l'action
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Liste des contrôleurs disponibles
$routes = [
    'home'     => 'HomeController',
    'login'    => 'LoginController',
    'register' => 'RegisterController'
];

// Vérification que le contrôleur existe
if (!array_key_exists($controller, $routes)) {
    die("Erreur : contrôleur introuvable.");
}

$controllerName = $routes[$controller];

// Vérification du fichier du contrôleur
$controllerPath = __DIR__ . "/controller/" . $controllerName . ".php";
if (!file_exists($controllerPath)) {
    die("Erreur : fichier contrôleur non trouvé.");
}

// Instanciation
$controllerInstance = new $controllerName();

// Vérification de la méthode
if (!method_exists($controllerInstance, $action)) {
    die("Erreur : action '$action' inexistante.");
}

// Appel de l'action
$controllerInstance->$action();
