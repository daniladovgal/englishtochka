<?php

if (count($_GET) > 0) {

	$api = array_keys($_GET)[0];
	$params = $_GET[$api];

	require_once "engine.php";

	$engine = new engine();

	$answer = $engine->answer($api, $params);

} else {
	$answer = '{"response":"Class not selected"}';
}

echo $answer;

?>
