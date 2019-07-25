<?php

/**
 * ******************
 * Crea la vista de usuario segun el enrutamiento
 * ******************
 */
class userRoute extends Router {

	const MAIN_ROUTE = PADRE."/".PRINCIPAL."/user";
	const MAIN_REQUIRE = RAIZ.SEP."admin".SEP."views".SEP."user";
	
	/**
	 * ******************
	 * Crea la vista principal de usuarios
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

			case "login":

				if (count($address) != 1) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				require self::MAIN_REQUIRE.SEP."login.php";

			break;

			case "crear":

				if (count($address) != 1 || !User::hasAccess(USER["access"], "user/crear")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				require self::MAIN_REQUIRE.SEP."create.php";
			break;

			case "modificar":

				if (count($address) > 2 || !User::hasAccess(USER["access"], "user/modificar")) {
					array_unshift($address, self::MAIN_ROUTE);
					require self::NOT_FOUND;
					return;
				}

				require self::MAIN_REQUIRE.SEP."search_bar.php";

				if (empty($address[1])) {
					require self::MAIN_REQUIRE.SEP."list.php";
					break;
				}

				$user = User::getUser([
					"search" => (string)$address[1]
				]);

				if (!$user["rsp"]) {
					require self::MAIN_REQUIRE.SEP."error_loading_user.php";
				} else {
					require self::MAIN_REQUIRE.SEP."modify_user.php";
				}

			break;

			case "logout":

				if(USER["logged"]) {
					$_SESSION["id"] = NULL;
				}				

				header("Location: ".PADRE."/".PRINCIPAL);
				exit;
			break;

			case "password":
				require self::MAIN_REQUIRE.SEP."password.php";
			break;

			default:
				array_unshift($address, self::MAIN_ROUTE);
				require self::NOT_FOUND;
		}
	}

}

?>