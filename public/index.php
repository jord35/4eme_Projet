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
| Shared - User
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/common/shared/user/UserManager.php';

/*
|--------------------------------------------------------------------------
| Services
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/common/service/AuthenticationService.php';
require_once ROOT_DIR . 'src/controllers/common/service/EditBookService.php';
require_once ROOT_DIR . 'src/controllers/common/service/MyAccountService.php';
require_once ROOT_DIR . 'src/controllers/common/service/MessagePageService.php';

/*
|--------------------------------------------------------------------------
| Feature - Home
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/HomeController.php';

/*
|--------------------------------------------------------------------------
| Feature - Signup
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/signup/Signup.php';
require_once ROOT_DIR . 'src/models/signup/SignupManager.php';
require_once ROOT_DIR . 'src/controllers/SignupController.php';

/*
|--------------------------------------------------------------------------
| Feature - Login
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/login/Login.php';
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

/*
|--------------------------------------------------------------------------
| Feature - My Account
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/myaccount/MyAccount.php';
require_once ROOT_DIR . 'src/models/myaccount/MyAccountManager.php';
require_once ROOT_DIR . 'src/controllers/MyAccountController.php';

/*
|--------------------------------------------------------------------------
| Feature - Public Account
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/PublicAccountController.php';

/*
|--------------------------------------------------------------------------
| Feature - Single Book
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/controllers/SingleBookController.php';

/*
|--------------------------------------------------------------------------
| Feature - Message
|--------------------------------------------------------------------------
*/
require_once ROOT_DIR . 'src/models/message/ConversationManager.php';
require_once ROOT_DIR . 'src/models/message/MessageManager.php';
require_once ROOT_DIR . 'src/models/common/service/MessagingService.php';
require_once ROOT_DIR . 'src/controllers/MessagesController.php';

$action = $_GET['action'] ?? null;

if ($action === 'login') {
    $controller = new LoginController();
    $controller->execute();
    exit;
}

if ($action === 'signup') {
    require_once ROOT_DIR . 'src/views/templates/signup.php';
    exit;
}

if ($action === 'signup-check-username') {
    $controller = new SignupController();
    $controller->checkUsername();
    exit;
}

if ($action === 'signup-check-email') {
    $controller = new SignupController();
    $controller->checkEmail();
    exit;
}

if ($action === 'signup-register') {
    $controller = new SignupController();
    $controller->register();
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

if ($action === 'home' || $action === null) {
    $controller = new HomeController();
    $controller->execute();
    exit;
}

if ($action === 'my-account') {
    $controller = new MyAccountController();
    $controller->execute();
    exit;
}

if ($action === 'public-account') {
    $controller = new PublicAccountController();
    $controller->execute();
    exit;
}

if ($action === 'single-book') {
    $controller = new SingleBookController();
    $controller->execute();
    exit;
}

if ($action === 'messages') {
    $controller = new MessagesController();
    $controller->execute();
    exit;
}

http_response_code(404);
echo 'Page non trouvee.';