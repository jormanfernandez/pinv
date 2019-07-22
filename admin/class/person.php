<?php

class Person {

	private $id;
	private $pid;
	private $fname;
	private $lname;
	private $cedula;
	private $error;
	private $insertedBy;
	private $dateCreated;

	/**
	 * ******************
	 * Crea una nueva persona
	 * @param $id {int} se envia si se va a buscar por id, string si se busca por pid
	 * ******************
	 */
	public function __construct ($id) {

		$search = [
			"searchBy" => is_string($id) ? "pid" : "id",
			"data" => $id
		];

		$this->build($search);
	}

	/**
	 * ******************
	 * Construye los datos de la persona si existe
	 * @param $data {array} Indica el metodo de busqueda y el parametro a usar
	 * ******************
	 */
	private function build(array $data) {

		global $db;

		$db->query("SELECT 
			p.id,
			p.pid,
			p.name AS firstName,
			p.lname AS lastName,
			p.cedula,
			p.created_at AS fechaCreado,
			p.inserted_by AS insertadoPor
		FROM personas p
		WHERE p.".$data["searchBy"]." = :search")
		->bind(":search", $data["data"]);

		if (!$db->ejecutar()) {

			$this->error = "Erro creando persona: ".$db->error;
			$db->cleanError();
			return;
		}

		if ($db->contar() != 1) {
			$this->error = "La persona no pudo ser encontrada";
			return;
		}

		$data = $db->simple();

		$this->id = $data["id"];
		$this->pid = $data["pid"];		
		$this->fname = $data["firstName"];
		$this->lname = $data["lastName"];
		$this->cedula = $data["cedula"];
		$this->dateCreated = $data["fechaCreado"];
		$this->insertedBy = intval($data["insertadoPor"]);
	}

	/**
	 * ******************
	 * Obtiene los datos de la persona
	 * @param $param {string} Indica el dato a obtener
	 * @return {array} Indica si la busqueda fue exitosa y el valor, en caso de que exista
	 * ******************
	 */
	public function get(string $param) : array {

		$rsp = [
			"rsp" => false,
			"data" => "Camino no encontrado"
		];

		switch ($param) {

			case "id":

				$rsp = [
					"rsp" => true,
					"data" => $this->id
				];

			break;

			case "pid":

				$rsp = [
					"rsp" => true,
					"data" => $this->pid
				];

			break;

			case "name":
				$rsp = [
					"rsp" => true,
					"data" => $this->fname
				];
			break;

			case "lname":
				$rsp = [
					"rsp" => true,
					"data" => $this->lname
				];
			break;

			case "fullName":
				$rsp = [
					"rsp" => true,
					"data" => $this->fname." ".$this->lname
				];
			break;

			case "cedula":
				$rsp = [
					"rsp" => true,
					"data" => $this->cedula
				];
			break;

			case "error":
				$rsp = [
					"rsp" => true,
					"data" => $this->error
				];
			break;

			case "created":
				$rsp = [
					"rsp" => true,
					"data" => $this->dateCreated
				];
			break;

			case "createdDate":
				$rsp = [
					"rsp" => true,
					"data" => gmdate("Y-m-d H:i:s ", $this->dateCreated)
				];
			break;

			default:
				$rsp["data"] = "Campo no encontrado";
		}

		return $rsp;
	}

	/**
	 * ******************
	 * Indica si la instancia tiene un error
	 * @return {bool}
	 * ******************
	 */
	public function hasError () : bool {
		return !empty($this->error);
	}

	/**
	 * ******************
	 * Realiza actualizaciones a la persona segun los indices enviados
	 * @param $data {array}
	 * @return {array}
	 * ******************
	 */
	public function update (array $data) : array {

		$return = [
			"rsp" => false,
			"data" => "Actualizacion no realizada",
			"steps" => []
		];

		if(count($data) < 1) {
			$return["data"] = "No se enviaron datos a actualizar";
			unset($return["steps"]);
			return $return;
		}

		// Itera sobre los datos enviados viendo que debe actualizar
		foreach ($data as $case => $value) {

			switch($case) {

				case "fname":

					$tmp = $this->updateFName($value);

					if($tmp["rsp"]) {
						$return["rsp"] = true;
					}

					$return["steps"][] = $tmp["data"];
					unset($tmp);
				break;

				case "lname":

					$tmp = $this->updateLName($value);

					if($tmp["rsp"]) {
						$return["rsp"] = true;
					}

					$return["steps"][] = $tmp["data"];
					unset($tmp);

				break;

				case "cedula":

					$tmp = $this->updateCedula($value);

					if($tmp["rsp"]) {
						$return["rsp"] = true;
					}

					$return["steps"][] = $tmp["data"];
					unset($tmp);
				break;
			}
		}

		$return["data"] = count($return["steps"]) > 0 ? join(" | ", $return["steps"]) : $return["data"];
		unset($return["steps"]);

		return $return;
	}

	/**
	 * ******************
	 * Actualiza el nombre de una persona
	 * @param $name {string}
	 * @return {array}
	 * ******************
	 */
	private function updateFName(string $name) : array {

		global $db;

		$rsp = [
			"rsp" => false,
			"data" => "Nombre no actualizado"
		];

		$name = trim($name);

		if (empty($name)) {

			$rsp["data"] = "Nombre no puede estar vacio";
			return $rsp;
		}

		$db->query("UPDATE personas SET name = :name WHERE id = :id")
		->multi_bind([
			":name" => $name,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$rsp["data"] = "Error cambiando nombre: ".$db->error;
			$db->cleanError();
			return $rsp;
		}

		$rsp = [
			"rsp" => true,
			"data" => "Nombre actualizado exitosamente"
		];

		return $rsp;
	}

	/**
	 * ******************
	 * Actualiza el apellido de una persona
	 * @param $name {string}
	 * @return {array}
	 * ******************
	 */
	private function updateLName(string $name) : array {
		
		global $db;

		$rsp = [
			"rsp" => false,
			"data" => "Apellido no actualizado"
		];

		$name = trim($name);

		if (empty($name)) {

			$rsp["data"] = "Apellido no puede estar vacio";
			return $rsp;
		}

		$db->query("UPDATE personas SET lname = :lname WHERE id = :id")
		->multi_bind([
			":lname" => $name,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$rsp["data"] = "Error cambiando nombre: ".$db->error;
			$db->cleanError();
			return $rsp;
		}

		$rsp = [
			"rsp" => true,
			"data" => "Apellido actualizado exitosamente"
		];

		return $rsp;
	}

	/**
	 * ******************
	 * Actualiza la cedula de una persona
	 * @param $cedula {string}
	 * @return {array}
	 * ******************
	 */
	private function updateCedula(string $cedula) : array {
		
		global $db;

		$rsp = [
			"rsp" => false,
			"data" => "Cedula no actualizada"
		];

		$cedula = trim($cedula);

		if (empty($cedula)) {

			$rsp["data"] = "Cedula no puede estar vacio";
			return $rsp;
		}

		$db->query("UPDATE personas SET cedula = :cedula WHERE id = :id")
		->multi_bind([
			":cedula" => $cedula,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$rsp["data"] = "Error cambiando cedula: ".$db->error;
			$db->cleanError();
			return $rsp;
		}

		$rsp = [
			"rsp" => true,
			"data" => "Cedula actualizada exitosamente"
		];

		return $rsp;
	}

	/**
	 * ******************
	 * @param $name {string} Nombre a asociar en los mensajes
	 * @return {string} Mensaje aleatorio
	 * ******************
	 */
	public static function sayHi (string $name = "") : string {

		$message = [
			"Hola".(!empty($name) ? ", ".$name : "").". Es un gran dia.",
			"Estos son tus accesos directos.",
			"Hola".(!empty($name) ? ", ".$name : "").". Espero te encuentres bien.",
			"Buen día".(!empty($name) ? ", ".$name : "").". ¿Cómo te encuentras el día de hoy?",
		];

		return texto($message[randomNumber(0, count($message) -1)]);
	}

	/**
	 * ******************
	 * Crea una nueva persona en el directorio
	 * @param $data {array} Datos a añadir de la persona
	 * @return {array} Respuesta de la funcion
	 * ******************
	 */
	public static function crear(array $data) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Builder not done"
		];

		$data["nombre"] = trim($data["nombre"]);
		$data["apellido"] = trim($data["apellido"]);
		$data["cedula"] = intval($data["cedula"]);

		if(empty($data["nombre"]) || empty($data["apellido"])) {

			$return["data"] = "El nombre y apellido no pueden estar vacios";
			return $return;
		}

		if ($data["cedula"] < 999999) {

			$return["data"] = "La cedula es incorrecta";
			return $return;
		}

		if (self::exists($data["cedula"])) {
			$return["data"] = "Ya existe una persona con este numero de cedula";
			return $return;
		}

		$db->query("INSERT INTO 
			personas(name, lname, cedula, inserted_by) 
		VALUES(:name, :lname, :cedula, :inserted_by)")
		->multi_bind([
			":name" => $data["nombre"],
			":lname" => $data["apellido"],
			":cedula" => $data["cedula"],
			":inserted_by" => $_SESSION["id"]
		]);

		if (!$db->ejecutar()) {

			$return["data"] = "Error creando persona: ".$db->error;
			return $return;
		}


		$return = [
			"rsp" => true,
			"data" => $data["nombre"]." ".$data["apellido"]." ha sido agregado exitosamente"
		];

		return $return;
	}

	/**
	 * ******************
	 * Verifica si un numero de cedula existe
	 * @param $cedula {int} Cedula a revisar
	 * @return {bool}
	 * ******************
	 */
	public static function exists (int $cedula) : bool {

		global $db;

		$db->query("SELECT id FROM personas WHERE cedula = :cedula")
		->bind(":cedula", $cedula);

		if (!$db->ejecutar()) {
			return false;
		}

		return $db->contar() > 0;
	}

	/**
	 * ******************
	 * Busca el public id de una cedula
	 * @param $cedula {int} Cedula a revisar
	 * @return {string}
	 * ******************
	 */
	public static function searchPid(string $cedula) : string {

		global $db;

		$db->query("SELECT pid FROM personas WHERE cedula = :cedula")
		->bind(":cedula", $cedula);

		if (!$db->ejecutar() || $db->contar() < 1) {
			return "";
		}

		return $db->simple()["pid"];
	}
}

?>