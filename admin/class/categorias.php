<?php

/**
 * ******************
 * Control de categorias
 * ******************
 */
class Categoria {

	private $error;
	private $id;
	private $nombre;

	/**
	 * ******************
	 * Crea una instancia
	 * @param $id {int} Identificador de la categoria
	 * ******************
	 */
	public function __construct(int $id) {

		global $db;

		$db->query("SELECT nombre FROM categorias WHERE id = :id")
		->bind(":id", $id);

		if (!$db->ejecutar()) {

			$this->error = "Error obteniendo categoria: ".$db->error;
			$db->cleanError();
			return;
		}

		if ($db->contar() != 1) {

			$this->error = "No se encontro la categoria";
			return;
		}

		$data = $db->simple();
		$this->id = $id;
		$this->nombre = $data["nombre"];
	}

	/**
	 * ******************
	 * Obtiene informacion de la instancia
	 * @param $data {string} Nombre de la categoria
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

			$this->error = "Ya existe una categoria con este nombre";
			return false;
		}

		$db->query("UPDATE categorias SET nombre = :nombre WHERE id = :id")
		->multi_bind([
			":nombre" => $name,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$this->error = "Error modificando categoria: ".$db->error;
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

		$db->query("DELETE FROM categorias WHERE id = :id")
		->bind(":id", $this->id);

		if (!$db->ejecutar()) {

			$this->error = "Error eliminando categoria";
			$db->cleanError();
			return false;
		}

		return true;
	}

	/**
	 * ******************
	 * Imprime todas las categorias
	 * @param $name {string} Nombre de la categoria
	 * @return {array}
	 * ******************
	 */
	public static function create (string $nombre = "") : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Categoria no creada"
		];

		$nombre = trim($nombre);

		if (empty($nombre)) {

			$return["data"] = "El nombre de la categoria no puede estar vacio";
			return $return;
		}

		if (self::exists($nombre)) {

			$return["data"] = "La categoria ya existe";
			return $return;
		}

		$db->query("INSERT INTO categorias(nombre) VALUES(:nombre)")
		->bind(":nombre", $nombre);

		if (!$db->ejecutar()) {

			$return["data"] = "Error creando categoria: ".$db->error;
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => "Categoria creada exitosamente"
		];

		return $return;
	}

	/**
	 * ******************
	 * Indica si una categoria existe
	 * @param $data {string | int} busqueda
	 * @return {bool}
	 * ******************
	 */
	public static function exists ($data) : bool {

		global $db;

		$search = is_int($data) ? "id = :data" : "LOWER(nombre) = LOWER(:data)";

		$db->query("SELECT id FROM categorias WHERE ".$search)
		->bind(":data", $data);

		return $db->ejecutar() && $db->contar() > 0;
	}
	
	/**
	 * ******************
	 * Imprime todas las categorias
	 * @return {array}
	 * ******************
	 */
	public static function getAll () : array {

		global $db;

		$db->query("SELECT * FROM categorias ORDER BY LOWER(nombre) ASC");

		return !$db->ejecutar() || $db->contar() < 1 ? [] : $db->todos();
	}
}

?>