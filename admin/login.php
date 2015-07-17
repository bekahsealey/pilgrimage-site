<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
		$error = '';
	if (isset($_POST['login'])) {
		session_start();
		$username = trim($_POST['username']);
		$password = trim($_POST['pwd']);
		//location to redirect on success
		$redirect = 'index.php';
		require_once('../includes/authenticate_mysqli.php');
	}
	
	$title_section = "Admin - Login";
	include('../includes/buildlist.php');
	$list_dir = './'; //file directory from which to get filenames
	$file_type = 'php'; //extension of files to list
	$prefix = '';  //set prefix to remove from displayed filename
	$extension = '.php'; //set extension to remove from displayed filename
	include('../includes/title-page-name.php');
	include('../includes/header.php');
?>
			<article>
				<header><h3><?php echo $title_section; ?></h3></header>
				<?php
			if ($error) {
				echo "<p>$error</p>";
			} elseif (isset($_GET['expired'])) {
				?>
				<p class="error">Your session has expired. Please log in again.</p>
				<?php } ?>
				<form id="form1" method="post">
					<fieldset>
						<legend>Login</legend>
						<ol>
							<li>
								<label for="username">Username:</label>
								<input type="text" name="username" id="username">
							</li>
							<li>
								<label for="pwd">Password:</label>
								<input type="password" name="pwd" id="pwd">
							</li>
							<li>
								<input name="login" type="submit" id="login" value="Log in">
							</li>
						</ol>
					</fieldset>
				</form>
				<footer></footer>
			</article>
		<?php
		include('../includes/footer.php');
		?>
