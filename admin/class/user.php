<?php

class User {

	protected static $salt = "iHjKaw5q36Xye29A98-X";

	private $id;
	private $nick;
	private $owner;
	private $status;
	private $access = [];
	private $error = "";

	/**
	 * ******************
	 * Crea un nuevo usuario
	 * @param $id {int | string} se envia int si se va a buscar por id, string si es por nick
	 * ******************
	 */
	public function __construct ($id) {

		$search = [
			"searchBy" => is_string($id) ? "nick" : "id",
			"data" => $id
		];

		$this->build($search);
	}

	/**
	 * ******************
	 * Construye los datos de un usuario si existe
	 * @param $data {array} Indica el metodo de busqueda y el parametro a usar
	 * ******************
	 */
	private function build(array $data) {

		global $db;

		$db->query("SELECT 
			u.id AS userId,
			u.nick,
			estatus.id AS idStatus,
			estatus.nombre AS nameStatus,
			u.person AS person
		FROM users u
		INNER JOIN estatus ON estatus.id = u.id
		WHERE u.".$data["searchBy"]." = :search")
		->bind(":search", $data["data"]);

		if (!$db->ejecutar()) {

			$this->error = "Erro creando usuario: ".$db->error;
			$db->cleanError();
			return;
		}

		if ($db->contar() != 1) {
			$this->error = "El usuario ".($data["searchBy"] == "id" ? "" : "\"".$data["data"]."\"")." no pudo ser encontrado";
			return;
		}

		$data = $db->simple();

		$this->id = $data["userId"];
		$this->nick = $data["nick"];
		$this->owner = new Person($data["person"]);
		$this->estatus = [
			"id" => $data["idStatus"],
			"name" => $data["nameStatus"]
		];
	}

	/**
	 * ******************
	 * Obtiene los datos de el usuario
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

			case "nick":

				$rsp = [
					"rsp" => true,
					"data" => $this->nick
				];

			break;

			case "error":
				$rsp = [
					"rsp" => true,
					"data" => $this->error
				];
			break;

			default:
				$rsp["data"] = "Campo no encontrado";
		}

		return $rsp;
	}

	/**
	 * ******************
	 * Indica si el usuario tiene un error almacenado
	 * @return {bool}
	 * ******************
	 */
	public function hasError() : bool {
		return !empty($this->error);
	}

	/**
	 * ******************
	 * Limpia errores almacenados
	 * @return {User} Devuelve la instancia
	 * ******************
	 */
	public function cleanError() : User {
		$this->error = "";
		return $this;
	}

	/**
	 * ******************
	 * Realiza actualizaciones al usuario segun los indices enviados
	 * @param $data {array}
	 * @return {array}
	 * ******************
	 */
	public function update(array $data) : array {

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

				case "password":

					$tmp = $this->updatePassword($value);

					if($tmp["rsp"]) {
						$return["rsp"] = true;
					}

					$return["steps"][] = $tmp["data"];
					unset($tmp);

				break;

				case "estatus":

					$tmp = $this->updateStatus($value);

					if($tmp["rsp"]) {
						$return["rsp"] = true;
					}

					$return["steps"][] = $tmp["data"];
					unset($tmp);
				break;

				case "access":

					if (!is_array($value)) {
						$return["steps"][] = "Accesos no incluidos";
						break;
					}

					$tmp = $this->updateAccess($value);

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
	 * Actualiza los accesos del usuario
	 * @param $access {array}
	 * @return {array}
	 * ******************
	 */
	private function updateAccess(array $access) : array {

		global $db;

		$rsp = [
			"rsp" => false,
			"data" => "Building Access Update"
		];

		$values = [];
		$debug = "";

		// Revisa que el acceso enviado exista
		foreach ($access as $idx => $value) {

			$id = Router::accessIndex($value);
			$debug .= "[".$value." - ".$idx."]";
			if ($id < 0) {
				continue;
			}

			$values[] = $id;
		}

		if (count($values) < 1) {
			$rsp["data"] = "No se encontraron accesos a actualizar | ".$debug;
			return $rsp;
		}

		$db->query("UPDATE users SET access = :access WHERE id = :id")
		->multi_bind([
			":id" => $this->id,
			":access" => json_encode($values)
		]);

		if (!$db->ejecutar()) {

			$rsp["data"] = "Error actualizando accesos: ".$db->error;
			$db->cleanError();
			return $rsp;
		}

		$rsp["rsp"] = true;
		$rsp["data"] = "Accesos actualizados";

		return $rsp;
	}

	/**
	 * ******************
	 * Actualiza el estatus de un usuario
	 * @param $status {status}
	 * @return {array}
	 * ******************
	 */
	private function updateStatus(int $status) : array {

		global $db;

		$rsp = [
			"rsp" => false,
			"data" => "Estatus no actualizado"
		];

		if ($status < 1) {

			$rsp["data"] = "Estatus invalido";
			return $rsp;
		}

		$db->query("SELECT id FROM estatus WHERE id = :id")
		->bind(":id", $status);

		if (!$db->ejecutar()) {

			$rsp["data"] = "Error actualizando estatus: ".$db->error;
			$db->cleanError();
			return $rsp;
		}

		if ($db->contar() != 1) {

			$rsp["data"] = "Estatus incorrecto";
			return $rsp;
		}

		$db->query("UPDATE users SET estatus = :estatus WHERE id = :id")
		->multi_bind([
			":estatus" => $status,
			":id" => $this->id
		]);

		if (!$db->ejecutar()) {

			$rsp["data"] = "Error actualizando estatus: ".$db->error;
			$db->cleanError();
			return $rsp;
		}

		$rsp = [
			"rsp" => true,
			"data" => "Estatus actualizado exitosamente"
		];

		return $rsp;
	}

	/**
	 * ******************
	 * Actualiza la contraseña de un usuario
	 * @param $new {string} Nueva contraseña
	 * @return {array}
	 * ******************
	 */
	private function updatePassword (string $new) : array {

		global $db;

		$rsp = [
			"rsp" => false,
			"data" => "Construyendo Contraseña"
		];

		$new = trim($new);

		if (empty($new)) {
			$rsp["data"] = "Contraseñas no pueden estar vacias";
			return $rsp;
		}

		// Actualiza la contraseña
		$db->query("UPDATE users u SET u.password = PASSWORD_HASH(:pass, :salt, :nick) WHERE u.id = :id")
		->multi_bind([
			":pass" => $new,
			":id" => $this->id,
			":salt" => self::$salt,
			":nick" => $this->nick
		]);


		if(!$db->ejecutar()) {

			$rsp["data"] = "Error ejecutando actualizacion: ".$db->$error;
			return $rsp;
		}

		$rsp = [
			"rsp" => true,
			"data" => "Contraseña actualizada exitosamente"
		];

		return $rsp;
	}

	/**
	 * ******************
	 * Obtiene un usuario ya sea por id o por nick
	 * @param $db {Database}
	 * @param $info {array} Busqueda a realizar ["search" => int || string]
	 * ******************
	 */
	public static function getUser (array $info) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => ""
		];

		$binder = is_int($info["search"]) ? "u.id" : "u.nick";

		// Buscamos los datos del usuario segun el parametro de busqueda
		$db->query("SELECT 
			u.id AS userId,
			u.nick AS nick,
			u.estatus AS idStatus,
			e.nombre AS nombreStatus,
			p.name AS personName,
			p.lname AS personLastName,
			u.access
		FROM users u, personas p, estatus e
		WHERE 
			u.person = p.id
			AND e.id = u.estatus
			AND ".$binder." = :search
		")
		->bind(":search", $info["search"]);

		if (!$db->ejecutar()) {

			$return["data"] = "Error [3]: ".$db->error;
			$db->cleanError();
			return $return;
		}

		if ($db->contar() != 1) {

			$return["data"] = "Usuario no encontrado";
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => $db->simple()
		];

		try {
			$return["data"]["access"] = json_decode($return["data"]["access"]);
		} catch (Exception $e) {
			$return["data"]["access"] = [];
		}

		return $return;
	}

	/**
	 * ************
	 * Iniciamos sesion de un usuario
	 * @param $data array
	 * @return array
	 * ************
	 */
	public static function logIn (array $data) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Usuario no logueado"
		];

		$db->query("SELECT 
			id
		FROM users
		WHERE PASSWORD_HASH(:password, :salt, :nick) = password
		AND id = :id
		")->multi_bind([
			":password" => $data["password"],
			":salt" => self::$salt,
			":nick" => $data["user"]["nick"],
			":id" => $data["user"]["userId"]
		]);

		if(!$db->ejecutar()) {
			$return["data"] = "Error [312]: ".$db->error;
			$db->cleanError();
			return $return;
		}

		if($db->contar() != 1) {
			$return["data"] = "Credenciales no coinciden";
			return $return;
		}

		$user = new User(intval($db->simple()["id"]));

		return [
			"rsp" => true,
			"data" => $user
		];
	}

	/**
	 * ************
	 * Crea un usuario al directorio
	 * @param $data array
	 * @return array
	 * ************
	 */
	public static function create (array $data) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Builder not done"
		];

		if (empty($data["nick"])) {

			$return["data"] = "El nick no puede estar vacio";
			return $return;
		}

		if (empty($data["cedula"])) {

			$return["data"] = "La cedula no puede estar vacia";
			return $return;
		}

		if (!Person::exists($data["cedula"])) {

			$return["data"] = "No existe una persona con la cedula: ".$data["cedula"];
			return $return;
		}

		if (self::hasUser($data["cedula"])) {

			$return["data"] = "Esta persona ya tiene un usuario creado";
			return $return;
		}

		if (self::nickExists($data["nick"])) {

			$return["data"] = "El nick seleccionado ya existe";
			return $return;
		}

		$values = [];

		// Revisa que el acceso enviado exista
		foreach ($data["access"] as $idx => $value) {

			$id = Router::accessIndex($value);
			if ($id < 0) {
				continue;
			}

			$values[] = $id;
		}

		$db->query("INSERT INTO users(nick, password, estatus, person, access) 
			SELECT 
				:nick, 
				PASSWORD_HASH(:password, :salt, :name), 
				1, 
				p.id,
				:access
			FROM personas AS p
			WHERE p.cedula = :cedula")
		->multi_bind([
			":nick" => $data["nick"],
			":name" => $data["nick"],
			":password" => $data["cedula"],
			":salt" => self::$salt,
			":cedula" => $data["cedula"],
			":access" => json_encode($values)
		]);

		if (!$db->ejecutar()) {

			$return["data"] = "No se pudo ingresar el usuario: ".$db->error;
			$db->cleanError();
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => "El usuario ".$data["nick"]." ha sido creado exitosamente"
		];

		return $return;
	}

	/**
	 * ************
	 * Verifica que una cedula no tenga un usuario creado
	 * @param $cedula {int}
	 * @return {bool}
	 * ************
	 */
	public static function hasUser (int $cedula) : bool {

		global $db;

		$db->query("SELECT u.id FROM users u 
		INNER JOIN personas p ON p.id = u.person
		WHERE p.cedula = :cedula")
		->bind(":cedula", $cedula);

		if (!$db->ejecutar())
			return false;

		return $db->contar() > 0;
	}

	/**
	 * ************
	 * Verifica que un nick no exista
	 * @param $nick {string}
	 * @return {bool}
	 * ************
	 */
	public static function nickExists (string $nick) : bool {

		global $db;

		$db->query("SELECT id FROM users 
		WHERE LOWER(nick) = LOWER(:nick)")
		->bind(":nick", $nick);

		if (!$db->ejecutar()) 
			return false;

		return $db->contar() > 0;
	}

	/**
	 * ************
	 * Devuelve los distintos estatus que puede tener un usuario
	 * @return {array}
	 * ************
	 */
	public static function getAllStatus() : array {

		global $db;

		$db->query("SELECT * FROM estatus");

		if (!$db->ejecutar() || $db->contar() < 1) {
			return [];
		}

		return $db->todos();
	}

	/**
	 * ************
	 * Verifica los permisos de ingreso a una ruta
	 * @param $access {array} permisos del usuario logueado
	 * @param $route {string} ruta a ingresar
	 * @return {bool}
	 * ************
	 */
	public static function hasAccess(array $access, string $route) : bool {

		$allow = false;

		foreach ($access as $key => $value) {
			if ($value["route"] == $route) {
				$allow = true;
				break;
			}
		}

		return $allow;
	}
}

?>