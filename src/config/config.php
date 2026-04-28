
<?php

// En fonction des routes utilisées, il est possible d'avoir besoin de la session ; on la démarre dans tous les cas.
session_start();

// Ici on met les constantes utiles,
// les données de connexions à la bdd
// et tout ce qui sert à configurer.

define('TEMPLATE_VIEW_PATH', __DIR__ . '/../views/templates/');
define('MAIN_VIEW_PATH', __DIR__ . '/../views/layouts/main.php');

$localConfigPath = __DIR__ . '/config.local.php';

if (is_file($localConfigPath)) {
    require_once $localConfigPath;
}

if (!defined('DB_HOST')) {
    define('DB_HOST', (string) (getenv('DB_HOST') ?: ''));
}

if (!defined('DB_NAME')) {
    define('DB_NAME', (string) (getenv('DB_NAME') ?: ''));
}

if (!defined('DB_USER')) {
    define('DB_USER', (string) (getenv('DB_USER') ?: ''));
}

if (!defined('DB_PASS')) {
    define('DB_PASS', (string) (getenv('DB_PASS') ?: ''));
}