<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
	require_once('includes/connection.php');
	require_once('includes/utility_funcs.php');
	
	set_include_path('includes');
	# Install PSR-0-compatible class autoloader
	spl_autoload_register(function($class){
		require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
	});
	# Get Markdown class
	use \Michelf\MarkdownExtra;
	
	
	// create database connection
	$conn = dbConnect('read');
	
	//prepare sql
	$sql = "SELECT title, page_content, html_content, DATE_FORMAT( created,  '%W, %M %e, %Y' ) AS created_date, filename, caption, category FROM php_pages
		LEFT JOIN php_images
		USING (image_id)
		JOIN php_categories
		USING (cat_id)
		WHERE php_pages.page_id = 4";
		
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	//prepare sql for random post
	$sql = "SELECT filename, title, postername FROM php_videos
			ORDER BY RAND()
			LIMIT 1";

	$rand = $conn->query($sql);
	$randRow = $rand->fetch_assoc();
	
	$title_section = "Home";
	include('includes/title-page-name.php');
	include('includes/header.php');
?>
			<article>
			
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
				<footer></footer>
				
			</article>
		<?php
		include('includes/footer.php');
		?>
