<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
	require_once('../includes/connection.php');
	require_once('../includes/utility_funcs.php');
	
	set_include_path('../includes');
	# Install PSR-0-compatible class autoloader
	spl_autoload_register(function($class){
		require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
	});
	# Get Markdown class
	use \Michelf\MarkdownExtra;
	
	
	// create database connection
	$conn = dbConnect('read');
	
	//prepare sql for about us
	$sql = "SELECT title, page_content, html_content, DATE_FORMAT( created,  '%W, %M %e, %Y' ) AS created_date, filename, caption, category FROM php_pages
		LEFT JOIN php_images
		USING (image_id)
		JOIN php_categories
		USING (cat_id)
		WHERE php_pages.page_id = 1";
		
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	//prepare sql for support us
	$sql = "SELECT title, page_content, html_content, DATE_FORMAT( created,  '%W, %M %e, %Y' ) AS created_date, filename, caption, category FROM php_pages
		LEFT JOIN php_images
		USING (image_id)
		JOIN php_categories
		USING (cat_id)
		WHERE php_pages.page_id = 8";
		
	$support = $conn->query($sql);
	$supportRow = $support->fetch_assoc();
	
	//prepare sql for random post
	$sql = "SELECT title, article, html_content, DATE_FORMAT( created,  '%W, %M %e, %Y' ) AS created_date, filename, caption, category FROM php_posts
			LEFT JOIN php_images
			USING (image_id)
			JOIN php_categories
			USING (cat_id)
			WHERE cat_id = 3
			ORDER BY RAND()
			LIMIT 1";

	$rand = $conn->query($sql);
	$randRow = $rand->fetch_assoc();
	
	$title_section = $row['title'];
	include('../includes/title-page-name.php');
	include('../includes/header.php');
?>
			<article id="about">
			
				<header><h3><?php if ($row) {
				echo $row['title']; 
				} else {
				echo 'No record found';
				} ?></h3></header>
				<?php
			if ($row && !empty($row['filename'])) {
				$filename = "/images/{$row['filename']}";
				$imageSize = getimagesize($_SERVER['DOCUMENT_ROOT'] . $filename);
			 ?>
			 <figure>
			 	<img src="<?php echo $filename; ?>" alt="<?php echo $row['caption']; ?>" <?php echo $imageSize[3]; ?>>
				<figcaption><?php echo $row['caption']; ?></figcaption>
			 </figure>
			<?php } if ($row) {
				$htmleArticle = htmlentities($row['page_content'], ENT_COMPAT, 'utf-8');
				echo MarkdownExtra::defaultTransform($htmleArticle);
				if ($row['html_content']) echo $row['html_content'];
			} ?>
				<footer><p>Published on <?php echo $row['created_date']; ?> in <?php echo ucwords($row['category']); ?></p></footer>
				
			</article>
			<article id="support">
			
				<header><h3><?php if ($supportRow) {
				echo $supportRow['title']; 
				} else {
				echo 'No record found';
				} ?></h3></header>
				<?php
			if ($supportRow && !empty($supportRow['filename'])) {
				$filename = "/images/{$supportRow['filename']}";
				$imageSize = getimagesize($_SERVER['DOCUMENT_ROOT'] . $filename);
			 ?>
			 <figure>
			 	<img src="<?php echo $filename; ?>" alt="<?php echo $supportRow['caption']; ?>" <?php echo $imageSize[3]; ?>>
				<figcaption><?php echo $supportRow['caption']; ?></figcaption>
			 </figure>
			<?php } if ($supportRow) {
				$htmleArticle = htmlentities($supportRow['page_content'], ENT_COMPAT, 'utf-8');
				echo MarkdownExtra::defaultTransform($htmleArticle);
				if ($supportRow['html_content']) echo $supportRow['html_content'];
			} ?>
				<footer><p>Published on <?php echo $supportRow['created_date']; ?> in <?php echo ucwords($supportRow['category']); ?></p></footer>
				
			</article>
			<article id="news">
			
				<header><h3>In the News</h3></header>
				<h4><?php if ($randRow) {
				echo $randRow['title']; 
				} else {
				echo 'No record found';
				} ?></h4>
				<?php
			if ($randRow && !empty($randRow['filename'])) {
				$filename = "/images/{$randRow['filename']}";
				$imageSize = getimagesize($_SERVER['DOCUMENT_ROOT'] . $filename);
			 ?>
			 <figure class="center">
			 	<img src="<?php echo $filename; ?>" alt="<?php echo $randRow['caption']; ?>" <?php echo $imageSize[3]; ?>>
				<figcaption><?php echo $randRow['caption']; ?></figcaption>
			 </figure>
			<?php } if ($randRow) {
				$htmleArticle = htmlentities($randRow['article'], ENT_COMPAT, 'utf-8');
				echo MarkdownExtra::defaultTransform($htmleArticle);
				if ($randRow['html_content']) echo $randRow['html_content']; ?>
				<p><a href="/media/">More News...</a></p>
			<?php } ?>
				<footer><p>Published on <?php echo $randRow['created_date']; ?> in <?php echo ucwords($randRow['category']); ?></p></footer>
				</article>
		<?php
		include('../includes/footer.php');
		?>
