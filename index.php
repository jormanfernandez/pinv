<?php 
session_start();
define("SEP", DIRECTORY_SEPARATOR);

/**
 * PINV - Trabajos a tu medida en PHP y JavaScript ES6
 * Carga de configuración inicial del sistema
 * @author <Jorman Fernandez>
 * @version 1.2
 */
require ".".SEP."admin".SEP."main".SEP."cfg.php";

echo !AJAX ? Router::fullView($ADDRESS) : Router::ajaxView($ADDRESS);

$db->fin();
exit;
?>