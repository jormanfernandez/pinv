<?php

/**
 * ******************
 * Control de marcas
 * ******************
 */
class Marca {

	private $error;
	private $id;
	private $nombre;

	/**
	 * ******************
	 * Crea una instancia
	 * @param $id {int} Identificador de la marca
	 * ******************
	 */
	public function __construct(int $id) {

		global $db;

		$db->query("SELECT nombre FROM marcas WHERE id = :id")
		->bind(":id", $id);

		if (!$db->ejecutar()) {

			$this->error = "Error obteniendo marca: ".$db->error;
			$db->cleanError();
			return;
		}

		if ($db->contar() != 1) {

			$this->error = "No se encontro la marca";
			return;
		}

		$data = $db->simple();
		$this->id = $id;
		$this->nombre = $data["nombre"];
	}

	/**
	 * ******************
	 * Obtiene informacion de la instancia
	 * @param $data {string} Nombre de la marca
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

			$this->error = "Ya existe una marca con este nombre";
			return false;
		}

		$db->query("UPDATE marcas SET nombre = :nombre WHERE id = :id")
		->multi_bind([
			":nombre" => $name,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$this->error = "Error modificando marca: ".$db->error;
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

		$db->query("DELETE FROM marcas WHERE id = :id")
		->bind(":id", $this->id);

		if (!$db->ejecutar()) {

			$this->error = "Error eliminando marca";
			$db->cleanError();
			return false;
		}

		return true;
	}

	/**
	 * ******************
	 * Imprime todas las marcas
	 * @param $name {string} Nombre de la marca
	 * @return {array}
	 * ******************
	 */
	public static function create (string $nombre = "") : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Marca no creada"
		];

		$nombre = trim($nombre);

		if (empty($nombre)) {

			$return["data"] = "El nombre de la marca no puede estar vacio";
			return $return;
		}

		if (self::exists($nombre)) {

			$return["data"] = "La marca ya existe";
			return $return;
		}

		$db->query("INSERT INTO marcas(nombre) VALUES(:nombre)")
		->bind(":nombre", $nombre);

		if (!$db->ejecutar()) {

			$return["data"] = "Error creando marca: ".$db->error;
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => "Marca creada exitosamente"
		];

		return $return;
	}

	/**
	 * ******************
	 * Indica si una marca existe
	 * @param $name {string || int} busqueda
	 * @return {bool}
	 * ******************
	 */
	public static function exists ($data) : bool {

		global $db;

		$search = is_int($data) ? "id = :data" : "LOWER(nombre) = LOWER(:data)";

		$db->query("SELECT id FROM marcas WHERE ".$search)
		->bind(":data", $data);

		return $db->ejecutar() && $db->contar() > 0;
	}
	
	/**
	 * ******************
	 * Imprime todas las marcas
	 * @return {array}
	 * ******************
	 */
	public static function getAll () : array {

		global $db;

		$db->query("SELECT * FROM marcas ORDER BY LOWER(nombre) ASC");

		return !$db->ejecutar() || $db->contar() < 1 ? [] : $db->todos();
	}
}

?>