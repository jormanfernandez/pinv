<?php

define("NAME", "PINV");
define("PRINCIPAL", strtolower(NAME));
define("ROUTE_PATH", "esteEsElCaminoParaLaRutaDeAccesoMVC");
define("LOCAL_TZ", "America/Caracas");
define("RAIZ", dirname(dirname(dirname(__FILE__))));
define("SITIO_ACTUAL",implode(array_slice(explode("?", "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"], 2),0,1)));
define("PADRE",implode(array_slice(explode("/".PRINCIPAL."/",strtolower(SITIO_ACTUAL)),0,1)));
define("URL_BASE", PADRE."/".PRINCIPAL."/");
define("XMLHTTP", !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest"  ? true : false);
define("AJAX", XMLHTTP && mb_strtolower($_SERVER["REQUEST_METHOD"]) == "post" ? true : false);

$ADDRESS = isset($_GET[ROUTE_PATH]) ? $_GET[ROUTE_PATH] : "";
$ADDRESS = explode("/", $ADDRESS);

require "pdo.php";
require "autoloader.php";

$db = new Database([
	"type" => "mysql",
	"host" => "127.0.0.1",
	"user" => "root",
	"dbName" => "pinv",
	"pass" => "",
	"port" => "3306" 
]);

if ($db->hasError()) {
	echo json_encode([
		"code" => 500,
		"message" => "Database error: ".$db->error,
		"data" => ""
	]);
	exit;
}

if (!isset($_SESSION["id"])) {

	define("USER", [
		"logged" => false,
		"error" => false,
		"perfil" => NULL,
		"status" => NULL,
		"statusName" => NULL,
		"owner" => NULL,
		"nick" => NULL,
		"id" => NULL,
		"access" => [
			[
				"name" => "Home",
				"route" => ""
			]
		]
	]);

} else {
	$user = User::getUser(["search" => intval($_SESSION["id"])]);

	if (!$user["rsp"]) {
		define("USER", [
			"logged" => false,
			"error" => true,
			"perfil" => NULL,
			"status" => NULL,
			"statusName" => NULL,
			"owner" => NULL,
			"nick" => NULL,
			"id" => NULL,
			"access" => [
				[
					"name" => "Home",
					"route" => ""
				]
			]
		]);
	} else {

		$access = [];
		$routes = [];
		try {

			$access = $user["data"]["access"];
			$routes = [];

			foreach(Router::access("routes") as $idx => $value) {

				if (array_search($idx, $access) === false ) {
					continue;
				}

				$routes[] = [
					"name" => Router::access("names")[$idx],
					"route" => Router::access("routes")[$idx],
				];
			}

			$access = $routes;
			unset($routes);

		} catch (Exception $e) {
			$access = [];
			$routes = [];
		}
		define("USER", [
			"logged" => true,
			"error" => false,
			"perfil" => NULL,
			"statusName" => $user["data"]["nombreStatus"],
			"status" => $user["data"]["idStatus"],
			"owner" => [
				"name" => $user["data"]["personName"],
				"last_name" => $user["data"]["personLastName"]
			],
			"nick" => $user["data"]["nick"],
			"id" => $_SESSION["id"],
			"access" => array_merge([
				[
					"name" => "Home",
					"route" => ""
				]
			],
			$access,
			[
				[
					"name" => "Cambiar contraseña",
					"route" => "user/password"
				],
				[
					"name" => "Salir",
					"route" => "user/logout"
				]
			])
		]);
	}
}

?>