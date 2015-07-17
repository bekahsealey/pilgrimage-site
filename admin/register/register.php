<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
		if (isset($_POST['register'])) {
		$username = trim($_POST['username']);
		$password = trim($_POST['pwd']);
		$retyped = trim($_POST['conf_pwd']);
		require_once('../../includes/register_user_mysqli.php');
	}
	
	$title_section = "Register";
	include('../../includes/title-page-name.php');
	include('../../includes/header.php');
?>
			<article>
				<header><h3>New User Registration</h3></header>
				<?php 
					if (isset($success)) {
						echo "<p>$success</p>";
					} elseif (isset($errors) && !empty($errors)) {
						echo '<ul>';
						foreach ($errors as $error) {
							echo "<li>$error</li>";
						}
						echo '</ul>';
					}
					?>
					<form method="post" id="form1">
						<fieldset>
							<legend><?php echo $title_section; ?></legend>
							<ol>
								<li>
									<label for="username">Username:</label>
									<input type="text" name="username" id="username" required>
								</li>
								<li>
									<label for="pwd">Password:</label>
									<input type="password" name="pwd" id="pwd" required>
								</li>
								<li>
									<label for="conf_pwd">Confirm Password:</label>
									<input type="password" name="conf_pwd" id="conf_pwd" required>
								</li>
								<li>
									<input type="submit" name="register" id="register" value="Register">
								</li>
							</ol>
						</fieldset>
					</form>
				<footer></footer>
			</article>
		<?php
		include('../../includes/footer.php');
		?>
