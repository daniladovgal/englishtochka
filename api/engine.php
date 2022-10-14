<?php

class engine {

	function answer($api, $params) {

		$api = explode("_", $api);

		if (empty($api[0]) or empty($api[1])) {
			return '{"response":"1"}';
		} else {

			$class = strtolower($api[0]);
			$function = strtolower($api[1]);
			$params = json_decode($params);
			$jsonerr = json_last_error() === JSON_ERROR_NONE;

			if ($jsonerr != 1) {
				return '{"response":"JSON error"}';
			} else {

				if (!file_exists($class . ".php")) {
					return '{"response":"Class doesnt exist"}';
				} else {

					require_once "db.php";
					require_once $class . ".php";

					if (!class_exists($class)) {
						return '{"response":"Class doesnt exist"}';
					} else {

						$apiclass = new $class();

						if (!method_exists($apiclass, $function)) {
							return '{"response":"Function doesnt exist"}';
						} else {

							return $apiclass->$function($params);
							

						}

					}

				}

			}

		}

	}

}

?>