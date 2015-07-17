<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
	require_once('../includes/blog_session_timeout.php');
	require_once('../includes/connection.php');
	// create db connection
	$conn = dbConnect('write');
	// initialize flag
	$OK = false;
	$deleted = false;
	
	//initialize prepared statement
	$stmt = $conn->stmt_init();
	// get details of selected record
	if (isset($_GET['article_id']) && !$_POST) {
		//prepare sql query
		$sql = 'SELECT article_id, title, DATE_FORMAT(created, "%W, %M %D, %Y") AS created
				FROM php_posts WHERE article_id = ?';
			if ($stmt->prepare($sql)) {
				// bind parameter and execute statement
				$stmt->bind_param('i', $_GET['article_id']);
				// bind the results to variables
				$stmt->bind_result($article_id, $title, $created);
				// execute the query, and fetch the result
				$stmt->execute();
				$stmt->fetch();
				}
		}
		// if confirm deletion button has been clicked, delete record
		if (isset($_POST['delete'])) {
			//prepare update query
			$sql = 'DELETE FROM php_posts WHERE article_id = ?';
			if ($stmt->prepare($sql)) {
				$stmt->bind_param('i', $_POST['article_id']);
				$stmt->execute();
				// if there's an error affected_rows is -1
				if ($stmt->affected_rows > 0) {
					$deleted = true;
				} else {
					$error = 'There was a problem deleting the record. ';
				}
			}
		}
	// redirect the page if deletion is successful,
	// cancel button clicked, or $_GET['article_id'] not defined
	if ($deleted || isset($_POST['cancel_delete']) || !isset($_GET['article_id'])) {
		header('Location: /admin/');
		exit;
	}
	// if any SQL query fails, display error message
	if (isset($stmt) && !$OK && !$deleted) {
		if(isset($error)) {
			$error = $stmt->error;
		}
	}
	
	$title_section = "Admin - Delete";
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
				
			<p><a href="index.php">View all entries </a></p>
			<?php
			if (isset($error) && !empty($error)) {
				echo "<p class='warning'>Error: $error</p>";
			} 
			if ($article_id == 0) { ?>
			<p class="warning">Invalid request: record does not exist.</p>
			<?php } else { ?>
			<h4 class="error">Please confirm that you want to delete the following item.<br>Note: This action cannot be undone.</h4>
			<?php } ?>
				<form id="form1" method="post">
					<fieldset>
						<legend><?php echo $title_page_name; ?></legend>
						<ol>
							<li><strong>Title: <?php echo htmlentities($title, ENT_COMPAT, 'utf-8'); ?></strong></li>
							<li><strong>Date: <?php echo $created; ?></strong></li>
							<li>
								<?php if(isset($article_id) && $article_id > 0) { ?>
								<input type="submit" name="delete" value="Confirm Deletion">
							<?php } ?>
								<input class="left" name="cancel_delete" type="submit" id="cancel_delete" value="Cancel">
							<?php if(isset($article_id) && $article_id > 0) { ?>
								<input class="right" name="article_id" type="hidden" value="<?php echo $article_id; ?>">
								<?php } ?>
							</li>
						</ol>
					</fieldset>
				</form>
				<footer></footer>
			</article>
		<?php
		include('../includes/footer.php');
		?>
