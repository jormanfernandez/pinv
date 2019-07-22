<?php

class inventarioAjaxRoute {
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

			case "agregar":

				$nombre = isset($_POST["nombre"]) ? strval($_POST["nombre"]) : "";
				$serial = isset($_POST["serial"]) ? strval($_POST["serial"]) : "";
				$descripcion = isset($_POST["descripcion"]) ? strval($_POST["descripcion"]) : "";
				$categoria = isset($_POST["categoria"]) ? intval($_POST["categoria"]) : 0;		
				$marca = isset($_POST["marca"]) ? intval($_POST["marca"]) : 0;		
				$estatus = isset($_POST["estado"]) ? intval($_POST["estado"]) : 0;

				$json = Articulo::crear([
					"nombre" => $nombre,
					"serial" => $serial,
					"descripcion" => $descripcion,
					"categoria" => $categoria,
					"marca" => $marca,
					"estatus" => $estatus
				]);

				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);

			break;

			case "asignar":

				$departamento = isset($_POST["departamento"]) ? intval($_POST["departamento"]): 0;
				$asignar = isset($_POST["asignar"]) ? strval($_POST["asignar"]) : "";
				$asignar = isset($_POST["asignar"]) ? strval($_POST["asignar"]) : "";
				$persona = isset($_POST["persona"]) ? intval($_POST["persona"]) : NULL;
				$puesto = isset($_POST["puesto"]) ? intval($_POST["puesto"]) : NULL;
				$estatus = isset($_POST["estatus"]) ? intval($_POST["estatus"]) : 0;
				$pid = isset($_POST["pid"]) ? strval($_POST["pid"]) : "";
				$accion = isset($_POST["accion"]) ? trim(strval($_POST["accion"])) : "";

				$articulo = new Articulo($pid);

				if ($articulo->hasError()) {
					$json["data"] = "Error leyendo articulo: ".$articulo->get("error"); 
					break;
				}

				$json = $articulo->asignar([
					"persona" => $persona,
					"puesto" => $puesto,
					"asignar" => $asignar,
					"departamento" => $departamento,					
					"estatus" => $estatus,
					"accion" => $accion
				]);

				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);

			break;

			case "modificar":

				$nombre = isset($_POST["nombre"]) ? strval($_POST["nombre"]) : "";
				$serial = isset($_POST["serial"]) ? strval($_POST["serial"]) : "";
				$descripcion = isset($_POST["descripcion"]) ? strval($_POST["descripcion"]) : "";
				$categoria = isset($_POST["categoria"]) ? intval($_POST["categoria"]) : 0;		
				$marca = isset($_POST["marca"]) ? intval($_POST["marca"]) : 0;		
				$estatus = isset($_POST["estado"]) ? intval($_POST["estado"]) : 0;
				$pid = isset($_POST["pid"]) ? strval($_POST["pid"]) : "";

				$articulo = new Articulo($pid);

				if ($articulo->hasError()) {
					$json["data"] = "Error leyendo articulo: ".$articulo->get("error"); 
					break;
				}

				$json = $articulo->update([
					"nombre" => $nombre,
					"serial" => $serial,
					"descripcion" => $descripcion,
					"categoria" => $categoria,
					"marca" => $marca,
					"estatus" => $estatus
				]);

				$json["code"] = $json["rsp"] ? 200 : 500;
				unset($json["rsp"]);

			break;

			default:
				$json["data"] = "[T]Ruta no encontrada";
		}

		return $json;
	}
}
?>