<?php

class userAjaxRoute {

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

			case "login":
				$json = self::logUser();
			break;

			case "crear":
				$json = self::createUser();
			break;

			case "modificar":
				$json = self::modificarUser();
			break;

			case "update_my_password":
				$json = self::updateMyPassword();
			break;

			case "reset_password":
				$json = self::resetPassword();
			break;

			default:
				$json["data"] = "[E]Ruta no encontrada";
		}

		return $json;
	}

	/**
	 * ************
	 * Logueamos a un usuario enviado por POST
	 * @return array
	 * ************
	 */
	private static function logUser() : array {

		$json = [
			"code" => 500,
			"data" => "Ruta no terminada"
		];

		$nick = isset($_POST["nick"]) && is_string($_POST["nick"]) ? trim($_POST["nick"]) : "";
		$password = isset($_POST["password"]) && is_string($_POST["password"]) ? trim($_POST["password"]) : "";


		if (isEmpty($nick) || isEmpty($password)) {
			$json["data"] = "Los datos no pueden estar vacios: [$nick, $password]";
			return $json;
		}

		$user = User::getUser([
			"search" => "".$nick
		]);

		if(!$user["rsp"]) {
			$json["data"] = "El usuario ingresado no existe";
			return $json;
		}

		if($user["data"]["idStatus"] != 1) {

			$json["data"] = join(", ", [
				"El usuario ".$user["data"]["nick"]." no esta activo",
				"se encuentra ".$user["data"]["nombreStatus"]
			]);
			return $json;
		}

		$user = User::logIn([
			"user" => $user["data"],
			"password" => $password
		]);

		if(!$user["rsp"]) {
			$json["data"] = $user["data"];
			return $json;
		}

		$_SESSION["id"] = $user["data"]->get("id")["rsp"] ? $user["data"]->get("id")["data"] : 0;

		$json = [
			"code" => 200,
			"data" => "Usuario $nick logueado"
		];
		
		return $json;
	}

	/**
	 * ************
	 * Creamos a un usuario enviado por POST
	 * @param $data {array} Informacion del usuario
	 * @return array
	 * ************
	 */
	public static function createUser () : array {

		$return = [
			"code" => 500,
			"data" => "Builder not done"
		];

		$data = [
			"nick" => isset($_POST["nick"]) ? trim($_POST["nick"]) : "",
			"cedula" => isset($_POST["cedula"]) ? intval($_POST["cedula"]) : "",
			"access" => isset($_POST["access"])  && is_array($_POST["access"]) ? $_POST["access"] : []
		];

		$return = User::create($data);

		$return["code"] = $return["rsp"] ? 200 : 500;
		unset($return["rsp"]);

		return $return;
	}

	/**
	 * ************
	 * Modificamos a un usuario enviado por POST
	 * @return array
	 * ************
	 */
	public static function modificarUser() : array {

		$return = [
			"code" => 500,
			"data" => "Builder not done"
		];

		$data = [
			"id" => isset($_POST["id"]) ? strval($_POST["id"]) : "",
			"estatus" => isset($_POST["estatus"]) ? intval($_POST["estatus"]) : 2,
			"access" => isset($_POST["access"])  && is_array($_POST["access"]) ? $_POST["access"] : []
		];

		$user = new User(strval($data["id"]));

		if ($user->hasError()) {

			$return["data"] = "Error: ".$user->get("error")["data"];
			$user->cleanError();
			return $return;
		}

		$return = $user->update($data);
		$return["code"] = $return["rsp"] ? 200 : 500;
		unset($return["rsp"]);

		return $return;
	}

	/**
	 * ************
	 * Actualiza la contraseña de un usuario
	 * @return array
	 * ************
	 */
	public static function updateMyPassword() {

		$return = [
			"code" => 500,
			"data" => "Builder not done"
		];

		$password = isset($_POST["nueva"]) ? trim(strval($_POST["nueva"])) : "";

		$user = new User(intval($_SESSION["id"]));

		if ($user->hasError()) {

			$return["data"] = "Error: ".$user->get("error")["data"];
			$user->cleanError();
			return $return;
		}

		$return = $user->update([
			"password" => $password
		]);
		$return["code"] = $return["rsp"] ? 200 : 500;
		unset($return["rsp"]);

		return $return;
	}

	/**
	 * ************
	 * Reinicia la contraseña de un usuario
	 * @return array
	 * ************
	 */
	public static function resetPassword() {

		$return = [
			"code" => 500,
			"data" => "Builder not done"
		];

		$id = isset($_POST["id"]) ? trim(strval($_POST["id"])) : "";

		$user = new User(strval($id));

		if ($user->hasError()) {

			$return["data"] = "Error: ".$user->get("error")["data"];
			$user->cleanError();
			return $return;
		}

		$return = $user->update([
			"password" => $user->get("nick")["data"]
		]);
		$return["code"] = $return["rsp"] ? 200 : 500;
		unset($return["rsp"]);

		return $return;
	}
}

?>