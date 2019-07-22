<?php 

class EstatusObjRoute extends Router {
	
	const MAIN_ROUTE = PADRE."/".PRINCIPAL."/estatus_obj";
	const MAIN_REQUIRE = RAIZ.SEP."admin".SEP."views".SEP."estatus_obj";
	
	/**
	 * ******************
	 * Crea la vista principal de usuarios
	 * @param $address {array} ruta a cargar
	 * ******************
	*/
	public static function fullGet (array $address) {

		if(count($address) != 1 || !User::hasAccess(USER["access"], "estatus_obj")) {
			$address = [self::MAIN_ROUTE];
			require self::NOT_FOUND;
			return;
		}

		require self::MAIN_REQUIRE.SEP."main.php";
	}
}

?>