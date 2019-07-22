<?php

/**
 * *************
 * Genera un numero aleatorio 
 * @param $min [int | float | double]
 * @param $max [int | float | double]
 * *************
 */
function randomNumber($min = 0, $max = 999999999) {
	return mt_rand($min, $max);
}

/**
 * *************
 * Comprueba si un numero esta en un rango dado
 * @param $number [int | float | double]
 * @param $min [int | float | double]
 * @param $max [int | float | double]
 * *************
 */
function between($number, $min, $max) : bool {
	return $number >= $min && $number <= $max;
}

/**
 * *************
 * Comprueba si un string esta vacio
 * @param $str [string]
 * @return bool
 * *************
 */
function isEmpty(string $str) : bool {
	$string = trim($str);
	return $string === false || $string === '';
}

/**
 * *************
 * Sanitizamos el output
 * @param $texto [string]
 * @return string
 * *************
 */
function texto(string $texto) : string {
	return htmlspecialchars(stripslashes($texto), ENT_QUOTES, "UTF-8");
}

?>