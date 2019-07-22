<?php 

class CategoriasRoute extends Router {
	
	const MAIN_ROUTE = PADRE."/".PRINCIPAL."/categorias";
	const MAIN_REQUIRE = RAIZ.SEP."admin".SEP."views".SEP."categorias";
	
	/**
	 * ******************
	 * Crea la vista principal de usuarios
	 * @param $address {array} ruta a cargar
	 * ******************
	*/
	public static function fullGet (array $address) {

		if(count($address) != 1 || !User::hasAccess(USER["access"], "categorias")) {
			$address = [self::MAIN_ROUTE];
			require self::NOT_FOUND;
			return;
		}

		require self::MAIN_REQUIRE.SEP."main.php";
	}
}

?>