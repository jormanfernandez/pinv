<?php

/**
 * ******************
 * Direccionamiento de vistas
 * ******************
 */
class Router {

	const NOT_FOUND =  RAIZ.SEP."admin".SEP."views".SEP."notFound.php";
	private static $access = [
		"Agregar personas",
		"Modificar personas",
		"Agregar usuarios",
		"Modificar usuarios",
		"Categorias",
		"Marcas",
		"Estatus de Objetos",
		"Departamentos",
		"Añadir a Inventario",
		"Modificar en Inventario",
		"Listar Inventario",
		"Asignar objeto",
		"Reportes",
	];
	private static $urls = [
		"persona/crear",
		"persona/modificar",
		"user/crear",
		"user/modificar",				
		"categorias",
		"marcas",
		"estatus_obj",
		"departamentos",
		"inventario/agregar",
		"inventario/modificar",
		"inventario/listar",
		"inventario/asignar",
		"reportes"
	];

	/**
	 * ******************
	 *	Coloca toda la estructura de la pagina
	 * @param $address {array} Ruta a cargar
	 * @return {string} html page
	 * ******************
	 */
	public static function fullView (array $address) : string {

		$content = "";
		$html = "";

		ob_start();

		if(USER["logged"] && USER["status"] != 1 && $address[0]."/".$address[1] !== "user/logout") {
			unset($_SESSION["id"]);
			require RAIZ.SEP."admin".SEP."views".SEP."user_unavailable.php";
		} else {
			switch ($address[0]) {

				case "":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					require RAIZ.SEP."admin".SEP."views".SEP."root".SEP."root.php";
				break;

				case "user":

					if(!USER["logged"] && $address[0]."/".$address[1] !== "user/login") {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					userRoute::fullGet($address);
				break;

				case "persona":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					personRoute::fullGet($address);

				break;

				case "categorias":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					CategoriasRoute::fullGet($address);
				break;

				case "marcas":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					MarcaRoute::fullGet($address);
				break;

				case "estatus_obj":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					EstatusObjRoute::fullGet($address);
				break;

				case "departamentos":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					DepartamentoRoute::fullGet($address);
				break;

				case "inventario":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					inventarioRoute::fullGet($address);

				break;

				case "reportes":

					if(!USER["logged"]) {
						userRoute::fullGet([
							"user",
							"login"
						]);
						break;
					}

					if(count($address) != 1 || !User::hasAccess(USER["access"], "reportes")) {
						require self::NOT_FOUND;
						break;
					}
					
					require RAIZ.SEP."admin".SEP."views".SEP."reportes".SEP."main.php";

				break;

				default:
					require self::NOT_FOUND;
				break;
			}
		}	

		$content = ob_get_clean();

		ob_start();

		require RAIZ.SEP."admin".SEP."views".SEP."main".SEP."main.php";

		$html = ob_get_clean();

		return $html;
	}

	/**
	 * ******************
	 *	Crea un json con la estructura necesaria solicitada
	 * @param $address {array} Ruta a cargar
	 * @return {string} json object
	 * ******************
	 */
	public static function ajaxView (array $address) : string {

		$json = [
			"code" => 200,
			"data" => "Server not done"
		];

		if ($address[0] != "api") {
			$json = [
				"code" => 500,
				"data" => "Solicitud no soportada"
			];
			return json_encode($json);
		}

		if (USER["logged"] && USER["status"] != 1) {

			$json = [
				"code" => 500,
				"data" => "Usuario ".USER["statusName"]
			];
			return json_encode($json);
		}

		array_splice($address, 0, 1);

		switch ($address[0]) {

			case "user":
				$json = userAjaxRoute::view($address);
			break;

			case "persona":
				$json = personAjaxRoute::view($address);
			break;

			case "categorias":
				$json = categoriasAjaxRoute::view($address);
			break;

			case "marcas":
				$json = marcasAjaxRoute::view($address);
			break;

			case "estatus_obj":
				$json = estatusObjAjaxRoute::view($address);
			break;

			case "departamentos":
				$json = departamentoAjaxRoute::view($address);
			break;

			case "inventario":
				$json = inventarioAjaxRoute::view($address);
			break;

			case "reportes":
				$json = reportesAjaxRoute::view($address);
			break;

			default:
				$json = [
					"code" => 500,
					"data" => "Ruta no encontrada"
				];
		}

		return json_encode($json);
	}

	/**
	 * ******************
	 *	Devuelve los distintos accesos que existen
	 * @return {array}
	 * ******************
	 */
	public static function printAccess () : array {
		return self::$access;
	}

	public static function access(string $type) : array {

		if ($type == "routes") {
			return self::$urls;
		} else {
			return self::$access;
		}
	}

	/**
	 * ******************
	 *	Verifica si un acceso existe y devuelve el indice
	 * @return {int}
	 * ******************
	 */
	public static function accessIndex (string $access) : int {
		$idx = array_search($access, self::$access);
		return $idx === false ? -1 : $idx;
	}
}

?>