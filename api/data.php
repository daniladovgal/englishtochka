<?php

class data {
	function add($params) {

		$answer = "";

		foreach ($params as $key => $value) {

			$value = strip_tags($value);
			$value = addslashes($value);

			switch ($key) {
			case "productid":
				$productid = $value;
				break;
			case "login":
				$login = $value;
				break;
			}
		}

		if (empty($productid) or empty($login)) {
			$answer = '{"response":"Data error"}';
		} else {

		$database = new database();
		$db = $database->opendb();

		if (!$db) {
			$answer = '{"response":"DB error"}';
		} else {
				
			$query = mysqli_query($db, "SELECT id,product_id FROM users LEFT JOIN orders_users ON product_id = '$productid' and user_id = users.id WHERE login = '$login'");

			$ans = mysqli_fetch_array($query);

				if (empty($ans)) {
					$answer = '{"response":"User isnt exist"}';
				} else {
					if (!empty($ans["product_id"])) {
						$answer = '{"response":"Row is exist"}';
					} else {
						$userid = $ans["id"];
						$query = mysqli_query($db,"INSERT INTO orders_users (product_id,user_id) VALUES ($productid,$userid)");
						if ($query) {
							$answer = '{"response":"Success"}';
						} else {
							$answer = '{"response":"DB error"}';
						}
					}
				}
			}
		}

		return $answer;

	}

	function get($params) {

		$answer = "";
		$num = "0,100";

		foreach ($params as $key => $value) {

			$value = strip_tags($value);
			$value = addslashes($value);

			switch ($key) {
			case "login":
				$login = $value;
				break;
			}
		}

		$database = new database();
		$db = $database->opendb();

		if (!$db) {
			$answer = '{"response":"DB error"}';
		} else {

			$query = mysqli_query($db, "SELECT id,name,login FROM users WHERE login = '$login'");

			if ($query) {

				$arr = mysqli_fetch_assoc($query);

				if (!empty($arr["id"])) {

					$userid = $arr["id"];

					$data = $arr;

					$query = mysqli_query($db, "SELECT sum(coins.price) AS cn FROM (SELECT price FROM coins WHERE user_id = $userid GROUP BY action) coins");

					if ($query) {
						$arr["coins"] = mysqli_fetch_assoc($query)["cn"];
					}

					$query = mysqli_query($db,"SELECT * FROM products LEFT JOIN orders_users ON orders_users.product_id = products.id and orders_users.user_id = $userid");

					if ($query) {
						while ($ans = mysqli_fetch_assoc($query)) {
							$arr["products"][] = $ans;
						}
					}

					$answer = '{"response":'.json_encode($arr).'}';

				} else {
					$answer = '{"response":"User isnt exist"}';
				}


			} else {
				$answer = '{"response":"Data error"}';
			}
		}

		return $answer;
	}
}

?>