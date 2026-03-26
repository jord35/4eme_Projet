

<?php

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/models/DBManager.php';
require_once __DIR__ . '/../src/models/AbstractEntity.php';
require_once __DIR__ . '/../src/models/AbstractEntityManager.php';
require_once __DIR__ . '/../src/models/message/Message.php';
require_once __DIR__ . '/../src/models/message/MessageManager.php';
require_once __DIR__ . '/../src/views/View.php';
require_once __DIR__ . '/../src/controllers/AbstractController.php';
require_once __DIR__ . '/../src/controllers/message/MessageController.php';

$controller = new MessageController();
$controller->index();
