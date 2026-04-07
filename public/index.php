<?php
// public/index.php

define('ROOT_DIR', __DIR__ . '/../');

require_once ROOT_DIR . 'src/config/config.php';

/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

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
| Shared - Book
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/common/shared/book/Book.php';
require_once ROOT_DIR . 'src/models/common/shared/book/BookManager.php';
require_once ROOT_DIR . 'src/controllers/common/helper/BookHelper.php';

/*
|--------------------------------------------------------------------------
| Services
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/common/service/AuthenticationService.php';
require_once ROOT_DIR . 'src/controllers/common/service/EditBookService.php';

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
| Feature - Edit-Book
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/EditBookController.php';

/*
|--------------------------------------------------------------------------
| Feature - Books
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/BooksController.php';

$action = $_GET['action'] ?? null;

if ($action === 'authenticate') {
    $controller = new LoginController();
    $controller->authenticate();
    exit;
}

if ($action === 'edit-book') {
    $controller = new EditBookController();
    $controller->execute();
    exit;
}

if ($action === 'books') {
    $controller = new BooksController();
    $controller->execute();
    exit;
}

require_once ROOT_DIR . 'src/views/templates/login.php';