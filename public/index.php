<?php

require_once __DIR__ . '/../src/config/config.php';

// Couches métier / data
require_once __DIR__ . '/../src/models/common/DBManager.php';
require_once __DIR__ . '/../src/models/common/AbstractEntity.php';
require_once __DIR__ . '/../src/models/common/AbstractEntityManager.php';

// Contrôleurs
require_once __DIR__ . '/../src/controllers/common/AbstractController.php';
require_once __DIR__ . '/../src/controllers/SignupController.php';

// Modèles signup
require_once __DIR__ . '/../src/models/signup/Signup.php';
require_once __DIR__ . '/../src/models/signup/SignupManager.php';

// Vue
require_once __DIR__ . '/../src/views/View.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$signupController = new SignupController();

if ($uri === '/signup' && $method === 'GET') {
    require_once __DIR__ . '/../src/views/templates/signup.php';
    exit;
}

if ($uri === '/signup/check-username' && $method === 'GET') {
    $signupController->checkUsername();
    exit;
}

if ($uri === '/signup/check-email' && $method === 'GET') {
    $signupController->checkEmail();
    exit;
}

if ($uri === '/signup/register' && $method === 'POST') {
    $signupController->register();
    exit;
}

http_response_code(404);
echo 'Page non trouvée';
