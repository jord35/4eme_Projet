
<?php
    
    // En fonction des routes utilisées, il est possible d'avoir besoin de la session ; on la démarre dans tous les cas. 
    session_start();

    // Ici on met les constantes utiles, 
    // les données de connexions à la bdd
    // et tout ce qui sert à configurer. 

define('TEMPLATE_VIEW_PATH', __DIR__ . '/../views/templates/');
define('MAIN_VIEW_PATH', __DIR__ . '/../views/layouts/main.php');

define('DB_HOST', 'localhost');
define('DB_NAME', 'tomtroc');
define('DB_USER', 'root');
define('DB_PASS', '');