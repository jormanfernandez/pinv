<?php
$directorios = array(
	RAIZ.SEP."admin".SEP."class".SEP
);

foreach($directorios as $direccion) {

	$files = glob($direccion."*.php");

	foreach($files as $archivo):
		require_once($archivo);
	endforeach;	
}
?>