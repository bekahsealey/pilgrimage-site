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
	
	//prepare events sql
	$sql = "SELECT title, page_content, html_content, DATE_FORMAT( created,  '%W, %M %e, %Y' ) AS created_date, filename, caption, category FROM php_pages
		LEFT JOIN php_images
		USING (image_id)
		JOIN php_categories
		USING (cat_id)
		WHERE php_pages.page_id = 3";
		
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	//prepare info sql
	$sql = "SELECT title, page_content, html_content, DATE_FORMAT( created,  '%W, %M %e, %Y' ) AS created_date, filename, caption, category FROM php_pages
		LEFT JOIN php_images
		USING (image_id)
		JOIN php_categories
		USING (cat_id)
		WHERE php_pages.page_id = 7";
		
	$info = $conn->query($sql);
	$infoRow = $info->fetch_assoc();

	$title_section = $row['title'];
	include('../includes/title-page-name.php');
	include('../includes/header.php');
?>
			<article id="brochure">
			
				<header><h3><?php if ($infoRow) {
				echo $infoRow['title']; 
				} else {
				echo 'No record found';
				} ?></h3></header>
				<?php
			if ($infoRow && !empty($infoRow['filename'])) {
				$filename = "/images/{$infoRow['filename']}";
				$imageSize = getimagesize($_SERVER['DOCUMENT_ROOT'] . $filename);
			 ?>
			 <figure>
			 	<a href="../files/sample-file.pdf"><img src="<?php echo $filename; ?>" alt="<?php echo $infoRow['caption']; ?>" <?php echo $imageSize[3]; ?>></a>
				<figcaption><?php echo $infoRow['caption']; ?></figcaption>
			 </figure>
			<?php } if ($infoRow) {
				$htmleArticle = htmlentities($infoRow['page_content'], ENT_COMPAT, 'utf-8');
				echo MarkdownExtra::defaultTransform($htmleArticle);
				if ($row['html_content']) echo $infoRow['html_content'];
			} ?>
				<footer><p>Published on <?php echo $infoRow['created_date']; ?> in <?php echo ucwords($row['category']); ?></p></footer>
				
			</article>
			<article id="showHide">
			
				<header class="article-top"><h3><?php if ($row) {
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
				<footer class="article-bottom"><p>Published on <?php echo $row['created_date']; ?> in <?php echo ucwords($row['category']); ?></p></footer>
				
			</article>
		<?php
		include('../includes/footer.php');
		?>
