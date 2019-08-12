<?php

class Articulo {

	private $id;
	private $nombre;
	private $marca;
	private $serial;
	private $categoria;
	private $fecha;
	private $estatus;
	private $descripcion;
	private $pid;

	private $error;

	/**
	 * ******************
	 * Crea la instancia
	 * @param $data {string | int} Metodo a buscar el articulo
	 * ******************
	 */
	public function __construct($data) {

		global $db;

		$sql = "SELECT 
			inv.*
		FROM inventario AS inv
		WHERE ".(is_int($data) ? "inv.id = :data" : "inv.serial = :data");

		$db->query($sql)
		->bind(":data", $data);

		if (!$db->ejecutar()) {

			$this->error = "Error creando instancia: ".$db->error;
			return;
		}

		if ($db->contar() < 1) {

			$this->error = "No se encontro un articulo con esta descripcion";
			return;
		}

		$data = $db->simple();

		$this->id = $data["id"];
		$this->pid = $data["pid"];
		$this->nombre = $data["nombre"];
		$this->serial = $data["serial"];
		$this->fecha = $data["fecha_registro"];
		$this->descripcion = $data["descripcion"];
		$this->marca = new Marca($data["marca"]);
		$this->estatus = new EstatusObj($data["estatus"]);
		$this->categoria = new Categoria($data["categoria"]);
	}

	/**
	 * ******************
	 * Obtener los datos de la instancia
	 * @param $param {string} Parametro a buscar
	 * ******************
	 */
	public function get(string $param) {

		switch ($param) {
			case "id":
				return $this->id;
			break;

			case "pid":
				return $this->pid;
			break;
			
			case "nombre":
				return $this->nombre;
			break;

			case "marca":
				return $this->marca;
			break;

			case "serial":
				return $this->serial;
			break;

			case "categoria":
				return $this->categoria;
			break;

			case "fecha":
				return $this->fecha;
			break;

			case "estatus":
				return $this->estatus;
			break;

			case "descripcion":
				return $this->nombre;
			break;

			case "error":
				return $this->error;
			break;

			default:
				return NULL;
			break;
		}
	}

	/**
	 * ******************
	 * Indica si tiene error
	 * @return {bool}
	 * ******************
	 */
	public function hasError() : bool {
		return !empty($this->error);
	}

	public function asignar(array $data) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Construccion iniciada"
		];

		if (empty($data["accion"])) {
			$data["accion"] = NULL;
		}

		switch ($data["asignar"]) {
			case "persona":

				$data["asignar"] = 1;

				$data["persona"] = Person::searchPid($data["persona"]);

				if (empty($data["persona"])) {
					$return["data"] = "La persona elegida no existe";
					return $return;
				}

				$data["persona"] = new Person($data["persona"]);
				$data["persona"] = $data["persona"]->get("id")["data"];
				$data["puesto"] = NULL;

			break;
			case "puesto":

				$data["asignar"] = 1;

				if ($data["puesto"] < 1) {

					$return["data"] = "El puesto es invalido";
					return $return;
				}

				$data["persona"] = NULL;
				
			break;
			case "nadie":
				$data["persona"] = NULL;
				$data["puesto"] = NULL;
				$data["asignar"] = NULL;
			break;
			default:

				$data["asignar"] = 1;
				if ($data["puesto"] < 1) {

					$return["data"] = "El puesto es invalido";
					return $return;
				}

				$data["persona"] = Person::searchPid($data["persona"]);

				if (empty($data["persona"])) {
					$return["data"] = "La persona elegida no existe";
					return $return;
				}

				$data["persona"] = new Person($data["persona"]);
				$data["persona"] = $data["persona"]->get("id")["data"];
			break;
		}

		if (!Departamento::idExists($data["departamento"])) {

			$return["data"] = "El departamento elegido no existe";
			return $return;
		}

		if (!EstatusObj::idExists($data["estatus"])) {

			$return["data"] = "El estatus elegido no existe";
			return $return;
		}

		$save = [];

		foreach($data as $idx => $value) {
			$save[":".$idx] = $value;
		}

		$data = $save;
		unset($save);

		$db->query("INSERT INTO log_inventario(item, accion, estatus, departamento, persona, asignado, puesto) 
			VALUES(:item, :accion, :estatus, :departamento, :persona, :asignar, :puesto)")
		->multi_bind(array_merge([
			":item" => $this->id
		], $data));

		if (!$db->ejecutar()) {

			$return["data"] = "Error realizando accion: ".$db->error;
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => "Accion realizada exitosamente"
		];

		return $return;
	}

	/**
	 * ******************
	 * Devuelve la fecha en un formato especifico
	 * @param $format {string} Formato a devolver
	 * @return {string} Fecha formateada
	 * ******************
	 */
	public function formatDate(string $format) : string {
		$date = date_create($this->date);
		return date_format($date, $format);
	}

	/**
	 * ******************
	 * Actualiza el articulo
	 * @param $data {array}
	 * @return {array}
	 * ******************
	 */
	public function update(array $data) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Ruta no terminada"
		];

		if(empty($data["nombre"])) {
			$data["nombre"] = NULL;
		}

		if(empty($data["descripcion"])) {
			$data["descripcion"] = NULL;
		}

		if (empty($data["serial"]) && empty($data["nombre"])) {
			$return["data"] = "Hay campos vacios";
			return $return;
		}

		if (self::serialExists($data["serial"], $this->id)) {

			$return["data"] = "El serial ingresado ya existe";
			return $return;
		}

		if (!Marca::exists($data["marca"])) {

			$return["data"] = "La marca elegida no existe";
			return $return;
		}

		if (!Categoria::exists($data["categoria"])) {

			$return["data"] = "La categoria elegida no existe";
			return $return;
		}

		$binder = [];

		foreach($data as $key => $value) {
			$binder[":".$key] = $value;
		}

		$data = $binder;

		unset($binder);

		$data[":id"] = $this->id;

		$db->query("UPDATE inventario
			SET nombre = :nombre, 
				serial = :serial, 
				marca = :marca, 
				categoria = :categoria, 
				estatus = :estatus, 
				descripcion = :descripcion
			WHERE id = :id
			")
		->multi_bind($data);

		if (!$db->ejecutar()) {

			$return["data"] = "Error modificando articulo: ".$db->error." | ".json_encode($data);
			$db->cleanError();
			return $return;
		}

		$return["rsp"] = true;

		$return["data"] = "Articulo modificado exitosamente";
		$return["id"] = $this->pid;

		return $return;
	}

	/**
	 * ******************
	 * Crea un nuevo articulo en el invenatrio
	 * @param $data {array} Datos a añadir de la persona
	 * @return {array} Respuesta de la funcion
	 * ******************
	 */
	public static function crear (array $data) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Item no cerado"
		];

		if(empty($data["nombre"])) {
			$data["nombre"] = NULL;
		}

		if(empty($data["descripcion"])) {
			$data["descripcion"] = NULL;
		}

		if (empty($data["serial"]) && empty($data["nombre"])) {
			$return["data"] = "Hay campos vacios";
			return $return;
		}

		if (self::serialExists($data["serial"])) {

			$return["data"] = "El serial ingresado ya existe";
			return $return;
		}

		if (!Marca::exists($data["marca"])) {

			$return["data"] = "La marca elegida no existe";
			return $return;
		}

		if (!Categoria::exists($data["categoria"])) {

			$return["data"] = "La categoria elegida no existe";
			return $return;
		}

		$binder = [];

		foreach($data as $key => $value) {
			$binder[":".$key] = $value;
		}

		$data = $binder;

		unset($binder);

		$db->query("INSERT INTO inventario(nombre, serial, marca, categoria, estatus, descripcion) 
			VALUES(:nombre, :serial, :marca, :categoria, :estatus, :descripcion)")
		->multi_bind($data);

		if (!$db->ejecutar()) {

			$return["data"] = "Error insertando articulo: ".$db->error;
			$db->cleanError();
			return $return;
		}

		$return["rsp"] = true;
		$return["data"] = "Articulo agregado exitosamente";
		$return["id"] = $data[":serial"];

		return $return;
	}

	/**
	 * ******************
	 * Verifica si un serial existe
	 * @param $serial {string}
	 * @return {bool}
	 * ******************
	 */
	public static function serialExists (string $serial, int $id = 0) : bool {

		global $db;

		$bind = [
			":serial" => $serial
		];

		$sql = "SELECT id FROM inventario WHERE serial = :serial";

		if ($id > 0) {
			$sql = "SELECT id FROM inventario WHERE serial = :serial AND id != :id";
			$bind[":id"] = $id;
		}

		$db->query($sql)
		->multi_bind($bind);

		if (!$db->ejecutar()) {
			$db->cleanError();
			return false;
		}

		return $db->contar() > 0;
	}

	/**
	 * ******************
	 * Buscamos todos los articulos cargados en el inventario
	 * @return {array}
	 * ******************
	 */
	public static function getAll() : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => ""
		];

		$db->query("SELECT id FROM inventario");

		if (!$db->ejecutar()) {

			$return["data"] = "Error buscando articulos: ".$db->error;
			return $return;
		}

		if ($db->contar() < 1) {

			$return["data"] = "No hay articulos cargados";
			return $return;
		}

		return [
			"rsp" => true,
			"data" => $db->todos()
		];
	}
}

?>