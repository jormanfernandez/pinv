<?php

/**
 * ******************
 * Crea la vista de personas segun el enrutamiento
 * ******************
 */
class personRoute extends Router {

	const MAIN_ROUTE = PADRE."/".PRINCIPAL."/persona";
	const MAIN_REQUIRE = RAIZ.SEP."admin".SEP."views".SEP."person";

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

			case "crear":

				if (count($address) != 1 || !User::hasAccess(USER["access"], "persona/crear")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				require self::MAIN_REQUIRE.SEP."create.php";
			break;

			case "modificar":

				if (count($address) > 2 || !User::hasAccess(USER["access"], "persona/modificar")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				require self::MAIN_REQUIRE.SEP."search_bar.php";

				if (empty($address[1])) {
					require self::MAIN_REQUIRE.SEP."list.php";
					break;
				}

				if(!Person::exists((string)$address[1])) {
					$error = "Error buscando a la persona: Numero de cedula no encontrado";
					require self::MAIN_REQUIRE.SEP."error_loading_person.php";
					break;
				}

				$pid = Person::searchPid($address[1]);

				if (empty($pid)) {
					$error = "Error buscando a la persona: Numero de cedula no encontrado";
					require self::MAIN_REQUIRE.SEP."error_loading_person.php";
					break;
				}

				$person = new Person(strval($pid));

				if ($person->hasError()) {
					$error = "Error buscando a la persona: ".$person->get("error")["data"];
					require self::MAIN_REQUIRE.SEP."error_loading_person.php";
				} else {
					require self::MAIN_REQUIRE.SEP."modify.php";
				}

			break;

			default:
				array_unshift($address, self::MAIN_ROUTE);
				require self::NOT_FOUND;
		}
	}
}

?>