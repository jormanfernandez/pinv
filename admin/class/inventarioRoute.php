<?php

/**
 * ******************
 * Crea la vista de personas segun el enrutamiento
 * ******************
 */
class inventarioRoute extends Router {

	const MAIN_ROUTE = PADRE."/".PRINCIPAL."/inventario";
	const MAIN_REQUIRE = RAIZ.SEP."admin".SEP."views".SEP."inventario";

	/**
	 * ******************
	 * Crea la vista principal de personas
	 * @param $address {array} ruta a cargar
	 * ******************
	*/
	public static function fullGet (array $address) {

		if(count($address) < 2) {
			$address = [self::MAIN_ROUTE];
			require self::NOT_FOUND;
			return;
		}

		array_splice($address, 0, 1);


		switch ($address[0]) {

			case "agregar":

				if (count($address) != 1 || !User::hasAccess(USER["access"], "inventario/agregar")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				require self::MAIN_REQUIRE.SEP."agregar.php";
			break;

			case "asignar":

				if (count($address) > 2 || !User::hasAccess(USER["access"], "inventario/asignar")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				$type = "asignar";

				require self::MAIN_REQUIRE.SEP."search_bar.php";

				if (empty($address[1])) {
					break;
				}

				$articulo = new Articulo(strval($address[1]));

				if ($articulo->hasError()) {

					$error = $articulo->get("error");
					require self::MAIN_REQUIRE.SEP."error.php";
					break;
				}

				require self::MAIN_REQUIRE.SEP."asignar.php";
			break;

			case "modificar":

				if (count($address) > 2 || !User::hasAccess(USER["access"], "inventario/modificar")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				$type = "modificar";

				require self::MAIN_REQUIRE.SEP."search_bar.php";

				if (empty($address[1])) {
					break;
				}

				$articulo = new Articulo(strval($address[1]));

				if ($articulo->hasError()) {

					$error = $articulo->get("error");
					require self::MAIN_REQUIRE.SEP."error.php";
					break;
				}

				require self::MAIN_REQUIRE.SEP."modificar.php";
			break;

			case "listar":

				if (count($address) != 1 || !User::hasAccess(USER["access"], "inventario/listar")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				$articulos = Articulo::getAll();

				if (!$articulos["rsp"]) {

					$error = $articulos["data"];
					require self::MAIN_REQUIRE.SEP."error.php";
					break;
				}

				require self::MAIN_REQUIRE.SEP."listar.php";
			break;

			default:
				array_unshift($address, self::MAIN_ROUTE);
				require self::NOT_FOUND;
		}
	}
}

?>