<?php 

/**
 * ************
 * Genera los distintos reportes
 * ************
 */
class Reporte {

	/**
	 * ************
	 * Busca los datos segun un serial
	 * @param $data {array}
	 * @return {array}
	 * ************
	 */
	public static function serial(array $data) : array {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Reporte no terminado"
		];

		$sql = "SELECT * FROM log_inventario";

		$where = [];
		$bind = [];

		if(!empty($data["serial"])) {
			$where[] = "item IN (SELECT id FROM inventario WHERE serial = :serial)";
			$bind[":serial"] = $data["serial"];
		}

		if(!empty($data["fecha_max"])) {
			$where[] = "DATE(fecha) <= :fecha_max";
			$bind[":fecha_max"] = $data["fecha_max"];
		}

		if(!empty($data["fecha_min"])) {
			$where[] = "DATE(fecha) >= :fecha_min";
			$bind[":fecha_min"] = $data["fecha_min"];
		}

		if (count($where) > 0) {
			$sql .= " WHERE ".join(" AND ", $where);
		}

		$sql .= " ORDER BY fecha DESC";

		$db->query($sql);

		if (count($bind) > 0) {
			$db->multi_bind($bind);
		}

		if(!$db->ejecutar()) {

			$return["data"] = "Error leyendo reporte: ".$db->error;
			return $return;
		}

		if($db->contar() < 1) {

			$return["data"] = "No se encontraron registros".$data["fecha_max"];
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => $db->todos()
		];

		return $return;
	}

	/**
	 * ************
	 * Busca los datos generales
	 * @param $data {array}
	 * @return {array}
	 * ************
	 */
	public static function general () {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Reporte no terminado"
		];

		$sql = "SELECT * FROM log_inventario";

		$where = [];
		$bind = [];

		if(!empty($data["fecha_max"])) {
			$where[] = "DATE(fecha) <= :fecha_max";
			$bind[":fecha_max"] = $data["fecha_max"];
		}

		if(!empty($data["fecha_min"])) {
			$where[] = "DATE(fecha) >= :fecha_min";
			$bind[":fecha_min"] = $data["fecha_min"];
		}

		if (count($where) > 0) {
			$sql .= " WHERE ".join(" AND ", $where);
		}

		$sql .= " ORDER BY fecha DESC";

		$db->query($sql);

		if (count($bind) > 0) {
			$db->multi_bind($bind);
		}

		if(!$db->ejecutar()) {

			$return["data"] = "Error leyendo reporte: ".$db->error;
			return $return;
		}

		if($db->contar() < 1) {

			$return["data"] = "No se encontraron registros".$data["fecha_max"];
			return $return;
		}

		$return = [
			"rsp" => true,
			"data" => $db->todos()
		];

		return $return;
	}

	/**
	 * ************
	 * Busca los datos agrupados en estadistica
	 * @param $data {array}
	 * @return {array}
	 * ************
	 */
	public static function estadistica (array $data) {

		global $db;

		$return = [
			"rsp" => false,
			"data" => "Reporte no terminado"
		];

		$data = [
			"asignados" => 0,
			"noAsignados" => 0,
			"estatus" => []
		];

		$sql = "SELECT 
			COUNT(IF(asignado IS NOT NULL, 1, NULL)) AS esta_asignado, 
			COUNT(IF(asignado IS NULL, 1, NULL)) AS no_asignado 
		FROM log_inventario";

		$where = [];
		$bind = [];

		if(!empty($data["fecha_max"])) {
			$where[] = "DATE(fecha) <= :fecha_max";
			$bind[":fecha_max"] = $data["fecha_max"];
		}

		if(!empty($data["fecha_min"])) {
			$where[] = "DATE(fecha) >= :fecha_min";
			$bind[":fecha_min"] = $data["fecha_min"];
		}

		if (count($where) > 0) {
			$sql .= " WHERE ".join(" AND ", $where);
		}

		$sql .= " ORDER BY fecha DESC";

		$db->query($sql);

		if (count($bind) > 0) {
			$db->multi_bind($bind);
		}

		if(!$db->ejecutar()) {

			$return["data"] = "Error leyendo reporte: ".$db->error;
			return $return;
		}

		$rsp = $db->simple();

		$data["asignados"] = $rsp["esta_asignado"];
		$data["noAsignados"] = $rsp["no_asignado"];

		$sql = "SELECT 
			COUNT(l.id) AS cantidad, 
			e.nombre AS estado
		FROM log_inventario AS l 
		INNER JOIN estatus_obj AS e ON e.id = l.estatus
		";

		if (count($where) > 0) {
			$sql .= " WHERE ".join(" AND ", $where);
		}

		$sql .= " GROUP BY l.estatus ORDER BY fecha DESC ";

		$db->query($sql);

		if (count($bind) > 0) {
			$db->multi_bind($bind);
		}

		if(!$db->ejecutar()) {

			$return["data"] = "Error leyendo reporte: ".$db->error;
			return $return;
		}

		if($db->contar() > 0)  {
			$rsp = $db->todos();
			$data["estatus"] = $rsp;
			unset($rsp);
		}

		$return = [
			"rsp" => true,
			"data" => $data
		];

		return $return;
	}
}
?>