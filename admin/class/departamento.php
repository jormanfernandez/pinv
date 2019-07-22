<?php

/**
 * ******************
 * Control de departamentos
 * ******************
 */
class Departamento {

	private $error;
	private $id;
	private $nombre;

	/**
	 * ******************
	 * Crea una instancia
	 * @param $id {int} Identificador del departamento
	 * ******************
	 */
	public function __construct(int $id) {

		global $db;

		$db->query("SELECT nombre FROM departamentos WHERE id = :id")
		->bind(":id", $id);

		if (!$db->ejecutar()) {

			$this->error = "Error obteniendo departamento: ".$db->error;
			$db->cleanError();
			return;
		}

		if ($db->contar() != 1) {

			$this->error = "No se encontro el departamento";
			return;
		}

		$data = $db->simple();
		$this->id = $id;
		$this->nombre = $data["nombre"];
	}

	/**
	 * ******************
	 * Obtiene informacion de la instancia
	 * @param $data {string} Nombre de el departamentos
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

			$this->error = "Ya existe un departamento con este nombre";
			return false;
		}

		$db->query("UPDATE departamentos SET nombre = :nombre WHERE id = :id")
		->multi_bind([
			":nombre" => $name,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$this->error = "Error modificando departamento: ".$db->error;
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

		$db->query("DELETE FROM departamentos WHERE id = :id")
		->bind(":id", $this->id);

		if (!$db->ejecutar()) {

			$this->error = "Error eliminando departamento";
			$db->cleanError();
			return false;
		}

		return true;
	}

	/**
	 * ******************
	 * Crea un departamento
	 * @param $name {string} Nombre de el departamento
	 * @return {array}
	 * ******************
	 */
	public static function create (string $nombre = "") : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Departamento no creado"
		];

		$nombre = trim($nombre);

		if (empty($nombre)) {

			$return["data"] = "El nombre de el departamento no puede estar vacio";
			return $return;
		}

		if (self::exists($nombre)) {

			$return["data"] = "El departamento ya existe";
			return $return;
		}

		$db->query("INSERT INTO departamentos(nombre) VALUES(:nombre)")
		->bind(":nombre", $nombre);

		if (!$db->ejecutar()) {

			$return["data"] = "Error creando departamento: ".$db->error;
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => "Departamento creado exitosamente"
		];

		return $return;
	}

	/**
	 * ******************
	 * Indica si un departamentos existe
	 * @param $name {string} Nombre de el departamentos
	 * @return {bool}
	 * ******************
	 */
	public static function exists (string $nombre) : bool {

		global $db;

		$db->query("SELECT id FROM departamentos WHERE LOWER(nombre) = LOWER(:nombre)")
		->bind(":nombre", $nombre);

		return $db->ejecutar() && $db->contar() > 0;
	}

	/**
	 * ******************
	 * Indica si un departamentos existe
	 * @param $id {int} id de el departamentos
	 * @return {bool}
	 * ******************
	 */
	public static function idExists (int $id) : bool {

		global $db;

		$db->query("SELECT id FROM departamentos WHERE LOWER(id) = LOWER(:id)")
		->bind(":id", $id);

		return $db->ejecutar() && $db->contar() > 0;
	}
	
	/**
	 * ******************
	 * Imprime todas los departamentos
	 * @return {array}
	 * ******************
	 */
	public static function getAll () : array {

		global $db;

		$db->query("SELECT * FROM departamentos ORDER BY LOWER(nombre) ASC");

		return !$db->ejecutar() || $db->contar() < 1 ? [] : $db->todos();
	}
}

?>