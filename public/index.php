<?php
// public/index.php

define('ROOT_DIR', __DIR__ . '/../');

require_once ROOT_DIR . 'src/config/config.php';

require_once ROOT_DIR . 'src/models/common/DBManager.php';
require_once ROOT_DIR . 'src/models/common/AbstractEntity.php';
require_once ROOT_DIR . 'src/models/common/AbstractEntityManager.php';

require_once ROOT_DIR . 'src/controllers/common/AbstractController.php';
require_once ROOT_DIR . 'src/views/View.php';

require_once ROOT_DIR . 'src/models/login/Login.php';
require_once ROOT_DIR . 'src/models/login/LoginManager.php';
require_once ROOT_DIR . 'src/models/myaccount/MyAccount.php';
require_once ROOT_DIR . 'src/models/myaccount/MyAccountManager.php';

require_once ROOT_DIR . 'src/controllers/LoginController.php';
require_once ROOT_DIR . 'src/controllers/MyAccountController.php';
require_once ROOT_DIR . 'src/controllers/MyAccountController.php';

$action = $_GET['action'] ?? null;

if ($action === 'authenticate') {
    $controller = new LoginController();
    $controller->authenticate();
    exit;
}

if ($action === 'myAccount') {
    $controller = new MyAccountController();
    $controller->show();
    exit;
}

if ($action === 'updateMyAccount') {
    $controller = new MyAccountController();
    $controller->update();
    exit;
}

require_once ROOT_DIR . 'src/views/templates/login.php';



