<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
	require_once('../includes/blog_session_timeout.php');
	require_once('../includes/connection.php');
		// create db connection
		$conn = dbConnect('write');
	
	if (isset($_POST['insert'])) {
		$error = '';
		if(($_POST['post_title']) == ''){
			// check for empty fields
			$error .= "Please enter a title. ";
			$errorTitle = true;
		}
		
		if(($_POST['article']) == ''){
			$error .= "Please enter an article. ";
			$errorArticle = true;
		}
	}
	
	if ((isset($_POST['insert'])) && ($error === '')) {
		// intialize flag
		$OK = false;
		//initialize prepared statement
		$stmt = $conn->stmt_init();
			//print_r($_POST); exit;
		//if a file has been uploaded, process it
		if(isset($_POST['upload_new']) && $_FILES['image']['error'] == 0) {
			$imageOK = false;
			
			require_once('../includes/classes/ThumbnailUpload.php');
			$max = 2500000;
			try {
				$upload = new Ps2_ThumbnailUpload('/your/path/images/');
				$upload->setThumbDestination('/your/path/images/thumbs/');
				$upload->setMaxSize($max);
				$upload->setThumbSuffix('');
				$upload->move();
				// after uploading and creating the thumbnail
				// get the name of the image
				// must add new lines to the processFile method in the ThumbnailUpload class see page 438
				// new lines will add file name to the filenames array
				// must add new property to the Upload class to store the filename $_filenames
				// must add new method to the Upload class to retrieve the filename getFilenames
				$names = $upload->getFilenames();
				// now $names contains an array with the names of the uploaded images (note: we are only uploading a single image)
				$messages = $upload->getMessages();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			
		// $names will be an empty array if the upload failed
		if ($names) {
			$sql = 'INSERT INTO php_images (filename, caption)
			VALUES (?, ?)';
			
			$stmt->prepare($sql);
			$stmt->bind_param('ss', $names[0], $_POST['caption']);
			$stmt->execute();
			$imageOK = $stmt->affected_rows;
		}
		//get the image's primary key or find out what went wrong
		if ($imageOK) {
			$image_id = $stmt->insert_id;
		} else {
			$imageError = implode(' ', $upload->getMessages());
		}
		} elseif (isset($_POST['image_id']) && !empty($_POST['image_id'])) {
			//get the primary key of a previously uploaded image from the select menu choice
			$image_id = $_POST['image_id'];
		}
		
		
		//don't insert blog details if the image failed to upload
		if (!isset($imageError)) {
			// if cat_id has been set use it, otherwise default to 1
			if (isset($_POST['cat_id'])) {
				$cat_id = $_POST['cat_id'];
			} else {
				$cat_id = 1;
			}
			// if $image_id has been set, insert it as a foreign key
			if (isset($image_id) && isset($_POST['html_content'])) {
				$sql = 'INSERT INTO php_posts (image_id, cat_id, title, article, html_content, created)
				VALUES(?, ?, ?, ?, ?, NOW())';
				$stmt->prepare($sql);
				$stmt->bind_param('iisss', $image_id, $cat_id, $_POST['post_title'], $_POST['article'], $_POST['html_content']);
			} else if (isset($image_id) && !isset($_POST['html_content'])) {
				$sql = 'INSERT INTO php_posts (image_id, cat_id, title, article, created)
				VALUES(?, ?, ?, ?, NOW())';
				$stmt->prepare($sql);
				$stmt->bind_param('iiss', $image_id, $cat_id, $_POST['post_title'], $_POST['article']);
			} else if (!isset($image_id) && isset($_POST['html_content'])) {
				$sql = 'INSERT INTO php_posts (cat_id, title, article, html_content, created)
				VALUES(?, ?, ?, ?, NOW())';
				$stmt->prepare($sql);
				$stmt->bind_param('isss', $cat_id, $_POST['post_title'], $_POST['article'], $_POST['html_content']);
			} else {
				//create sql
				$sql = 'INSERT INTO php_posts (cat_id, title, article, created)
				VALUES(?, ?, ?, NOW())';
				$stmt->prepare($sql);
				$stmt->bind_param('iss', $cat_id, $_POST['post_title'], $_POST['article']);
			}
			//execute and get number of affected rows
			$stmt->execute();
			$OK = $stmt->affected_rows;
		}
		// redirect if successful or display error
		if ($OK && !isset($imageError)) {
			header('Location: /admin/');
			exit;
		} else { 
		$error = $stmt->error;
		if (isset($imageError)) {
			$error .= ' ' . $imageError;
		}
		}
	}
		
	$title_section = "Admin - New Post";
	$js = '<script src="../js/showHide.js"></script>';
	
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
				<?php include('../includes/syntax-guide.php'); ?>
				<?php
				if (isset($error)) {
					echo "<p class=\"error\">Error: $error</p>";
				} ?>
					<form id="form2" method="post" enctype="multipart/form-data">
						<fieldset>
							<legend>Create New Blog Entry</legend>
							<ol>
								<li <?php if(isset($errorTitle)) echo 'class="error"'?>>
									<label for="post_title">Title:</label>
									<input type="text" name="post_title" id="post_title" value="<?php if (isset($error)) {
										echo htmlentities($_POST['post_title'], ENT_COMPAT, 'utf-8'); } ?>">
								</li>
								<li <?php if(isset($errorArticle)) echo 'class="error"'?>>
									<label for="article">Article (use <a href="http://daringfireball.net/projects/markdown/syntax#html">Markdown</a>):</label>
									<textarea name="article" cols="60" rows="24" id="article"><?php if (isset($error)) {
										echo htmlentities($_POST['article'], ENT_COMPAT, 'utf-8'); } ?></textarea>
								</li>
								<li>
									<label for="html_content">HTML Content:</label>
									<textarea name="html_content" cols="60" rows="4" id="html_content"><?php if (isset($error)) {
										echo htmlentities($_POST['html_content'], ENT_COMPAT, 'utf-8'); } ?></textarea>
								</li>
								<li class="left">
								<label for="cat_id">Category:</label>
								<select name="cat_id" id="cat_id">
									<option value="">Select Category</option>
										<?php
										// get the list of categories
										$getCats = 'SELECT cat_id, category
										FROM php_categories ORDER BY category';
										$categories = $conn->query($getCats);
										while ($row = $categories->fetch_assoc()) {
											?>
											<option value="<?php echo $row['cat_id']; ?>"
											<?php
											if (isset($_POST['cat_id']) && $row['cat_id'] == $_POST['cat_id']) {
												echo 'selected';
											}
											?>><?php echo ucwords($row['category']); ?></option>
											<?php } ?>
								</select>
							</li>
								<li class="left">
								<label for="image_id">Uploaded image:</label>
								<select name="image_id" id="image_id">
									<option value="">Select image</option>
										<?php
										// get the list of images
										$getImages = 'SELECT image_id, filename
										FROM php_images ORDER BY filename';
										$images = $conn->query($getImages);
										while ($row = $images->fetch_assoc()) {
											?>
											<option value="<?php echo $row['image_id']; ?>"
											<?php
											if (isset($_POST['image_id']) && $row['image_id'] == $_POST['image_id']) {
												echo 'selected';
											}
											?>><?php echo $row['filename']; ?></option>
											<?php } ?>
								</select>
							</li>
							<li id="allowUpload" class="clear">
								<label for="upload_new">
								<input type="checkbox" name="upload_new" id="upload_new">
								Upload new image</label>
							</li>
							<li class="optional">
								<label for="image">Select image:</label>
								<input type="file" name="image" id="image">
							</li>
							<li class="optional">
								<label for="caption">Caption:</label>
								<input name="caption" type="text" id="caption">
							</li>
							<li>
								<input name="insert" type="submit" id="insert" value="Create">
							</li>
						</ol>
					</fieldset>
				</form>
				<footer></footer>
			</article>
		<?php
		include('../includes/footer.php');
		?>
