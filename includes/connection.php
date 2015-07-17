<?php
function dbConnect($usertype, $connectionType = 'mysqli') {
	$host = 'hostname';
	$db = 'database name';
	if ($usertype == 'read') {
		$user = 'database username';
		$pwd = 'database user password';
	} elseif ($usertype == 'write') {
		$user = 'database username';
		$pwd = 'database user password';
	} else {
		exit('Unrecognized connection type');
	}
	if ($connectionType == 'mysqli') {
		$conn = new mysqli($host, $user, $pwd, $db);
		if ($conn->connect_error) {
			die('Cannot open database');
		}
		return $conn;
	} else {
		try {
			return new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
		} catch (PDOException $e) {
			echo 'Cannot connect to database';
			exit;
		}
	}
}