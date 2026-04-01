<?php
// public/index.php

define('ROOT_DIR', __DIR__ . '/../');

require_once ROOT_DIR . 'src/config/config.php';

/*
|--------------------------------------------------------------------------
| Common - Models
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/common/DBManager.php';
require_once ROOT_DIR . 'src/models/common/AbstractEntity.php';
require_once ROOT_DIR . 'src/models/common/AbstractEntityManager.php';

/*
|--------------------------------------------------------------------------
| Common - Controllers / Views
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/common/AbstractController.php';
require_once ROOT_DIR . 'src/views/View.php';

/*
|--------------------------------------------------------------------------
| Shared - Picture
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/common/shared/picture/Picture.php';
require_once ROOT_DIR . 'src/models/common/shared/picture/PictureVariant.php';
require_once ROOT_DIR . 'src/models/common/shared/picture/PictureVariantManager.php';
require_once ROOT_DIR . 'src/models/common/shared/picture/PictureManager.php';
require_once ROOT_DIR . 'src/controllers/common/helper/PictureHelper.php';

/*
|--------------------------------------------------------------------------
| Feature - Login
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/login/Login.php';
require_once ROOT_DIR . 'src/models/login/LoginManager.php';
require_once ROOT_DIR . 'src/controllers/LoginController.php';

/*
|--------------------------------------------------------------------------
| Feature - Test Picture
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/TestPictureController.php';

$action = $_GET['action'] ?? null;

if ($action === 'authenticate') {
    $controller = new LoginController();
    $controller->authenticate();
    exit;
}

if ($action === 'test-picture') {
    $controller = new TestPictureController();
    $controller->index();
    exit;
}

require_once ROOT_DIR . 'src/views/templates/login.php';