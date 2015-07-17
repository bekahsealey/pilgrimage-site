<?php
require_once('classes/CheckPassword.php');
$usernameMinChars = 6;
$errors = array();
if (strlen($username) < $usernameMinChars) {
	$errors[] = "Username must be at least $usernameMinChars characters.";
}
if (preg_match('/\s/', $username)) {
	$errors[] = 'Username should not contain spaces.';
}
$checkPwd = new Ps2_CheckPassword($password, 10);
$checkPwd->requireMixedCase();
$checkPwd->requireNumbers(2);
$checkPwd->requireSymbols();

$passwordOK = $checkPwd->check();
if (!$passwordOK) {
	$errors = array_merge($errors, $checkPwd->getErrors());
}
if ($password != $retyped) {
	$errors[] = "Your passwords don't match.";
}
if (!$errors) {
	// include the connection file
	require_once('connection.php');
	$conn = dbConnect('write');
	// create a salt using the current timestamp
	$salt = time();
	//encrypt the password and salt
	$pwd = sha1($password . $salt);
	//prepare sql statement
	$sql = 'INSERT INTO php_users (username, salt, pwd)
			VALUES (?, ?, ?)';
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);
	// bind parameters and insert the details into the database
	$stmt->bind_param('sis', $username, $salt, $pwd);
	$stmt->execute();
	if ($stmt->affected_rows == 1) {
		$success = "$username has been registered.  You may now <a href='/admin/login.php'>log in</a>.";
	} elseif ($stmt->errno == 1062) {
		$errors[] = "$username is already in use.  Please choose another username.";
	} else {
		$errors[] = 'Sorry, there was a problem with the database.';
	}
}