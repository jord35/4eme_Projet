<?php

require_once './config/config.php';

require_once './models/DBManager.php';
require_once './models/AbstractEntity.php';
require_once './models/AbstractEntityManager.php';

require_once './models/message/Message.php';
require_once './models/message/MessageManager.php';

require_once './views/View.php';

require_once './controllers/MessageController.php';

$controller = new MessageController();
$controller->index();
