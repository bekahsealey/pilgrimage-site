<?php
require_once('connection.php');
$conn = dbConnect('read');
// get the username's details from the database
$sql = 'SELECT salt, pwd FROM php_users WHERE username = ?';
// initialize and prepare statement
$stmt = $conn->stmt_init();
$stmt->prepare($sql);
// bind the input parameter
$stmt->bind_param('s', $username);
// bind the result, using a new variable for the password
$stmt->bind_result($salt, $storedPwd);
$stmt->execute();
$stmt->fetch();

// check for a matching record
if (sha1($password . $salt) == $storedPwd) {
	$_SESSION['authenticated'] = 'Wibbly Wobbly Timey Wimey stuff';
	$_SESSION['start'] = time();
	session_regenerate_id();
	header("Location: $redirect");
	exit;
} else {
	$error = 'Invalid username or password.';
}

?>