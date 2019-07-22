<?php 

class departamentoAjaxRoute {

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

			case "get_all":
				$json["data"] = Departamento::getAll();
				$json["code"] = 200;
			break;

			case "agregar":

				$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";

				$json = Departamento::create($nombre);
				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);
			break;

			case "modificar":

				$id = isset($_POST["id"]) ? intval($_POST["id"]) : "";
				$nombre = isset($_POST["nombre"]) ? strval($_POST["nombre"]) : "";

				$marca = new Departamento($id);

				if ($marca->hasError()) {
					$json["data"] = "Error modificando departamento: ".$marca->get("error")["data"];
					break;
				}

				$json["code"] = $marca->update($nombre) ? 200 : 500;
				$json["data"] = $json["code"] == 500 ? $marca->get("error")["data"] : "Departamento modificado exitosamente";

			break;

			case "eliminar":

				$id = isset($_POST["id"]) ? intval($_POST["id"]) : "";

				$marca = new Departamento($id);

				if ($marca->hasError()) {
					$json["data"] = "Error eliminando categoria: ".$marca->get("error")["data"];
					break;
				}
				$json["code"] = $marca->eliminar() ? 200 : 500;
				$json["data"] = $json["code"] == 500 ? $marca->get("error")["data"] : "Departamento eliminado exitosamente";

			break;

			default:
				$json["data"] = "[E]Ruta no encontrada";
		}

		return $json;
	}
}
?>