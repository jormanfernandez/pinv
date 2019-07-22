<?php 

class categoriasAjaxRoute {

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
				$json["data"] = Categoria::getAll();
				$json["code"] = 200;
			break;

			case "agregar":

				$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";

				$json = Categoria::create($nombre);
				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);
			break;

			case "modificar":

				$id = isset($_POST["id"]) ? intval($_POST["id"]) : "";
				$nombre = isset($_POST["nombre"]) ? strval($_POST["nombre"]) : "";

				$categoria = new Categoria($id);

				if ($categoria->hasError()) {
					$json["data"] = "Error modificando categoria: ".$categoria->get("error")["data"];
					break;
				}

				$json["code"] = $categoria->update($nombre) ? 200 : 500;
				$json["data"] = $json["code"] == 500 ? $categoria->get("error")["data"] : "Categoria modificada exitosamente";

			break;

			case "eliminar":

				$id = isset($_POST["id"]) ? intval($_POST["id"]) : "";

				$categoria = new Categoria($id);

				if ($categoria->hasError()) {
					$json["data"] = "Error eliminando categoria: ".$categoria->get("error")["data"];
					break;
				}
				$json["code"] = $categoria->eliminar() ? 200 : 500;
				$json["data"] = $json["code"] == 500 ? $categoria->get("error")["data"] : "Categoria eliminada exitosamente";

			break;

			default:
				$json["data"] = "[E]Ruta no encontrada";
		}

		return $json;
	}
}
?>