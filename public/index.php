<?php

require_once __DIR__ . '/../src/config/config.php';


require_once __DIR__ . '/../src/models/common/DBManager.php';
require_once __DIR__ . '/../src/models/common/AbstractEntity.php';
require_once __DIR__ . '/../src/models/common/AbstractEntityManager.php';

require_once __DIR__ . '/../src/models/home/Home.php';
require_once __DIR__ . '/../src/models/home/HomeManager.php';


require_once __DIR__ . '/../src/views/View.php';


require_once __DIR__ . '/../src/controllers/common/AbstractController.php';

require_once __DIR__ . '/../src/controllers/HomeController.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
