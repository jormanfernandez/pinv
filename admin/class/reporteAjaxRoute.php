<?php 

class reportesAjaxRoute {

	const MAIN_REQUIRE = RAIZ.SEP."admin".SEP."views".SEP."reportes";

	/**
	 * ************
	 * Obtenemos la vista en JSON
	 * @return array
	 * ************
	 */
	public static function view ( array $route ) : array {

		$json = [
			"code" => 500,
			"data" => "Ruta no terminada"
		];

		array_splice($route, 0, 1);

		switch($route[0]) {

			case "general":

				$json = self::reporteGeneral([
					"fecha_min" => isset($_POST["fecha_min"]) ? strval($_POST["fecha_min"]) : "",
					"fecha_max" => isset($_POST["fecha_max"]) ? strval($_POST["fecha_max"]) : ""
				]);

				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);
			break;
			case "estadistica":

				$json["data"] = "Reporte no terminado";

				$json = self::reporteEstadistica([
					"fecha_min" => isset($_POST["fecha_min"]) ? strval($_POST["fecha_min"]) : "",
					"fecha_max" => isset($_POST["fecha_max"]) ? strval($_POST["fecha_max"]) : ""
				]);

				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);
			break;

			case "serial":

				$data = [
					"serial" => isset($_POST["serial"]) ? strval($_POST["serial"]) : "",
					"fecha_min" => isset($_POST["fecha_min"]) ? strval($_POST["fecha_min"]) : "",
					"fecha_max" => isset($_POST["fecha_max"]) ? strval($_POST["fecha_max"]) : ""
				];

				$json = self::reporteSerial($data);

				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);
			break;

			break;

			default:
				$json["data"] = "[E]Ruta no encontrada";
		}

		return $json;
	}

	/**
	 * ************
	 * Reporte basado en el serial
	 * @return array
	 * ************
	 */
	private static function reporteSerial (array $data) : array {

		$return = [
			"rsp" => false,
			"data" => "Reporte no terminado"
		];

		$return = Reporte::serial($data);

		if (!$return["rsp"]) {
			return $return;
		}

		$content = "";

		ob_start();
		require self::MAIN_REQUIRE.SEP."serial.php";
		$content = ob_get_clean();
		$return["data"] = $content;

		unset($content);

		return $return;
	}

	/**
	 * ************
	 * Reporte de todos los articulos
	 * @return array
	 * ************
	 */
	private static function reporteGeneral (array $data) : array {

		$return = [
			"rsp" => false,
			"data" => "Reporte no terminado"
		];

		$return = Reporte::general($data);

		if (!$return["rsp"]) {
			return $return;
		}

		$content = "";

		ob_start();
		require self::MAIN_REQUIRE.SEP."serial.php";
		$content = ob_get_clean();
		$return["data"] = $content;

		unset($content);

		return $return;
	}

	/**
	 * ************
	 * Reporte contabilizador
	 * @return array
	 * ************
	 */
	public static function reporteEstadistica (array $data) : array {

		$return = [
			"rsp" => false,
			"data" => "Reporte no terminado"
		];

		$return = Reporte::estadistica($data);

		if (!$return["rsp"]) {
			return $return;
		}

		$content = "";

		ob_start();
		require self::MAIN_REQUIRE.SEP."data.php";
		$content = ob_get_clean();
		$return["data"] = $content;

		unset($content);

		return $return;
	}
}
?>