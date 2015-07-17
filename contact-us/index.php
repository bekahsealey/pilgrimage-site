<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
	#Google recaptcha 
	require_once('../includes/recaptchalib.php');
	$public_key = ''; //get keys https://www.google.com/recaptcha/admin
	$private_key = ''; 
	
	// Style Google reCAPTCHA via javascript
	$js_head = '<script type="text/javascript">var RecaptchaOptions = {theme : \'clean\'};</script>';

	#Email Validation and Sending
	$errors = array();
	$missing = array();
	//check if the form has been submitted
	if (isset($_POST['send'])) {
		//email processing script
		$to = 'you@email.com'; 
		$subject = 'Contact from your website';
		//list expected fields
		$expected = array('name', 'email', 'comments');
		//set required fields
		$required = array('name', 'email', 'comments');
		//create headers for email
		$headers = "From: You<you@email.com>\r\n";
		$headers .= 'Content-Type: text/plain; charset=utf-8';
	
	//Place results of receptcha_check_answer in $response
	$response = recaptcha_check_answer($private_key, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
	//Use the object to check if the is-valid property is not true
	if (!$response->is_valid) {
		$errors['recaptcha'] = true;
	}
	//var_dump($response);exit; //debugging: the var_dump function will display information - if an object or an array the results will be recursive
	
	#mail processing script	
	require('../includes/_processmail.inc_01.php');
		if (isset($mailSent)) {
			if($mailSent) {
				header('Location: thank-you.php');
				exit;
			}
		}
	} //close if(isset($_POST['send']))
	
	$title_section = "Contact Us";
	include('../includes/title-page-name.php');
	include('../includes/header.php');
?>
			<article>
			
				<header><h3><?php echo $title_section; ?></h3></header>
				<h4>By Mail:</h4>
				<p class="center">Fr. Andrew Kurz<br>
				5930 Humboldt Road<br>
				Luxemburg, WI 54217</p>
				<?php if ($_POST && isset($suspect)) { ?>
			<h4 class="error">Sorry, your mail could not be sent.  Please try later.</h4>
		<?php } elseif ($missing || $errors) { ?>
			<p class="error">Please complete the missing item(s) indicated.</p>
		<?php } ?>
		<form id="feedback" method="post">
			<fieldset>
				<legend>By Email:</legend>
				<ol>
					<li<?php if ($missing && in_array('name', $missing)) echo ' class="error"'; ?>>
						<?php if ($missing && in_array('name', $missing)) { ?>
						<strong>Please enter your name</strong>
						<?php } ?>
						<label for="name">Name:</label>
						<input name="name" id="name" type="text" class="formbox"
						<?php if (isset($name)) {
							echo 'value="' . htmlentities($name, ENT_COMPAT, 'UTF-8') . '"';
						} ?>>
					</li>
					<li<?php if ( ($missing && in_array('email', $missing)) || (isset($errors['email'])) ) echo ' class="error"'; ?>>
						<?php if ($missing && in_array('email', $missing)) { ?>
						<strong>Please enter your email</strong>
						<?php } elseif (isset($errors['email'])) { ?>
						<strong>Invalid email address</strong>
						<?php } ?>
						<label for="email">Email:</label>
						<input name="email" id="email" type="text" class="formbox"
						<?php if (isset($email)) {
							echo 'value="' . htmlentities($email, ENT_COMPAT, 'UTF-8') . '"';
						} ?>>
					</li>
					<li<?php if ($missing && in_array('comments', $missing)) echo ' class="error"'; ?>>
						<?php if ($missing && in_array('comments', $missing)) { ?>
						<strong>Please enter your comments</strong>
						<?php } ?>
					<label for="comments">Comments:</label>
					<textarea name="comments" id="comments" cols="60" rows="8"><?php if (isset($comments)) {
							echo htmlentities($comments, ENT_COMPAT, 'UTF-8');
						} ?></textarea>
					</li>
					<li<?php if (isset($errors['recaptcha'])) echo ' class="error" '; ?>>
						<?php if (isset($errors['recaptcha'])) { ?>
							<p class="error">The values don't match. Try again.</p>
						<?php }
						echo recaptcha_get_html($public_key); ?>
					</li>
					<li>
						<input name="send" id="send" type="submit" value="Send email">
					</li>
					
				</ol>
			</fieldset>
		</form>
				<footer></footer>
				
			</article>
		<?php
		include('../includes/footer.php');
		?>
