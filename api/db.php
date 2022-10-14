<?php

class Database {

	function opendb() {

		$dbname = "englishtochka";
		$dblogin = "admin";
		$dbpass = "excite2612";
		$dbhost = "localhost";

		$db = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
		mysqli_set_charset($db, "utf8mb4");

		if ($db) {
			return $db;
		} else {
			return false;
		}

	}

	function closedb($db) {

		mysqli_close($db);

	}

}

?>