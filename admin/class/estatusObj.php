<?php

/**
 * ******************
 * Control de estatus_obj
 * ******************
 */
class EstatusObj {

	private $error;
	private $id;
	private $nombre;

	/**
	 * ******************
	 * Crea una instancia
	 * @param $id {int} Identificador de la estatus
	 * ******************
	 */
	public function __construct(int $id) {

		global $db;

		$db->query("SELECT nombre FROM estatus_obj WHERE id = :id")
		->bind(":id", $id);

		if (!$db->ejecutar()) {

			$this->error = "Error obteniendo estatus: ".$db->error;
			$db->cleanError();
			return;
		}

		if ($db->contar() != 1) {

			$this->error = "No se encontro el estatus";
			return;
		}

		$data = $db->simple();
		$this->id = $id;
		$this->nombre = $data["nombre"];
	}

	/**
	 * ******************
	 * Obtiene informacion de la instancia
	 * @param $data {string} Nombre de el estatus
	 * @return {array}
	 * ******************
	 */
	public function get(string $data) : array {

		$return = [
			"rsp" => true,
			"data" => ""
		];

		switch ($data) {
			case "error":
				$return["data"] = $this->error;			
			break;

			case "id":
				$return["data"] = $this->id;			
			break;

			case "nombre":
				$return["data"] = $this->nombre;			
			break;
			
			default:
				$return = [
					"rsp" => false,
					"data" => "Valor no encontrado"
				];
			break;
		}

		return $return;
	}

	/**
	 * ******************
	 * Dice si tiene error
	 * @return {bool}
	 * ******************
	 */
	public function hasError() : bool {
		return !empty($this->error);
	}

	public function update (string $name) : bool {

		global $db;

		if (self::exists($name)) {

			$this->error = "Ya existe un estatus con este nombre";
			return false;
		}

		$db->query("UPDATE estatus_obj SET nombre = :nombre WHERE id = :id")
		->multi_bind([
			":nombre" => $name,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$this->error = "Error modificando estatus: ".$db->error;
			$db->cleanError();
			return false;
		}

		return true;
	}

	/**
	 * ******************
	 * Elimina la instancia de la base de datos
	 * @return {bool}
	 * ******************
	 */
	public function eliminar() : bool {

		global $db;

		$db->query("DELETE FROM estatus_obj WHERE id = :id")
		->bind(":id", $this->id);

		if (!$db->ejecutar()) {

			$this->error = "Error eliminando estatus";
			$db->cleanError();
			return false;
		}

		return true;
	}

	/**
	 * ******************
	 * Imprime todas las estatus_obj
	 * @param $name {string} Nombre de el estatus
	 * @return {array}
	 * ******************
	 */
	public static function create (string $nombre = "") : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Estatus no creado"
		];

		$nombre = trim($nombre);

		if (empty($nombre)) {

			$return["data"] = "El nombre de el estatus no puede estar vacio";
			return $return;
		}

		if (self::exists($nombre)) {

			$return["data"] = "El estatus ya existe";
			return $return;
		}

		$db->query("INSERT INTO estatus_obj(nombre) VALUES(:nombre)")
		->bind(":nombre", $nombre);

		if (!$db->ejecutar()) {

			$return["data"] = "Error creando estatus: ".$db->error;
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => "Estatus creado exitosamente"
		];

		return $return;
	}

	/**
	 * ******************
	 * Indica si un estatus existe
	 * @param $name {string} Nombre de el estatus
	 * @return {bool}
	 * ******************
	 */
	public static function exists (string $nombre) : bool {

		global $db;

		$db->query("SELECT id FROM estatus_obj WHERE LOWER(nombre) = LOWER(:nombre)")
		->bind(":nombre", $nombre);

		return $db->ejecutar() && $db->contar() > 0;
	}

	/**
	 * ******************
	 * Indica si un estatus existe
	 * @param $id {int} id de el estatus
	 * @return {bool}
	 * ******************
	 */
	public static function idExists (int $id) : bool {

		global $db;

		$db->query("SELECT id FROM estatus_obj WHERE id = :id")
		->bind(":id", $id);

		return $db->ejecutar() && $db->contar() > 0;
	}
	
	/**
	 * ******************
	 * Imprime todas los estatus_obj
	 * @return {array}
	 * ******************
	 */
	public static function getAll () : array {

		global $db;

		$db->query("SELECT * FROM estatus_obj ORDER BY LOWER(nombre) ASC");

		return !$db->ejecutar() || $db->contar() < 1 ? [] : $db->todos();
	}
}

?>