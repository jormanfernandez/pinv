<?php

class personAjaxRoute {
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

			case "crear":

				$json = [
					"code" => 500,
					"data" => "Creando backend"
				];

				$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
				$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "";
				$cedula = isset($_POST["cedula"]) ? $_POST["cedula"] : "";

				$json = Person::crear([
					"nombre" => $nombre,
					"apellido" => $apellido,
					"cedula" => $cedula
				]);

				$json["code"] = !$json["rsp"] ? 500 : 200;
				unset($json["rsp"]);

			break;

			case "modificar":

				$json = [
					"code" => 500,
					"data" => "Creando backend"
				];

				$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
				$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "";
				$cedula = isset($_POST["cedula"]) ? $_POST["cedula"] : "";
				$pid = isset($_POST["pid"]) ? $_POST["pid"] : "";

				if (empty($nombre) || empty($apellido) || empty($cedula)) {
					$json["data"] = "Hay campos vacios";
					break;
				}

				$person = new Person(strval($pid));

				if ($person->hasError()) {
					$json["data"] = "Error creando usuario: ".$person->get("error")["rsp"];
					break;
				}

				$json = $person->update([
					"fname" => $nombre,
					"lname" => $apellido,
					"cedula" => $cedula,
				]);

				$json["code"] = !$json["rsp"] ? 500 : 200;
				unset($json["rsp"]);

			break;

			default:
				$json["data"] = "[T]Ruta no encontrada";
		}

		return $json;
	}
}
?>