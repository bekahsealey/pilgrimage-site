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
	$sql = 'SELECT * , DATE_FORMAT( created,  "%W, %M %e, %Y" ) AS created_date, category
			FROM php_posts
			LEFT JOIN php_images
			USING ( image_id ) 
			JOIN php_categories
			USING ( cat_id ) 
			WHERE cat_id = 3
			ORDER BY created DESC '; //sorting by created from the database not the alias
	$result = $conn->query($sql);
	
	$title_section = "Media";
	include('../includes/title-page-name.php');
	include('../includes/header.php');
?>
			<?php
				while ($row = $result->fetch_assoc()) {
			?>
			<article>
			
				<header><h3><?php echo $row['title']; ?></h3></header>
				<?php
			if ($row && !empty($row['filename'])) {
				$filename = "/images/{$row['filename']}";
				$imageSize = getimagesize($_SERVER['DOCUMENT_ROOT'] . $filename);
			 ?>
			 <figure class="center">
			 	<img src="<?php echo $filename; ?>" alt="<?php echo $row['caption']; ?>" <?php echo $imageSize[3]; ?>>
				<figcaption><?php echo $row['caption']; ?></figcaption>
			 </figure>
			<?php } if ($row) {
				$htmleArticle = htmlentities($row['article'], ENT_COMPAT, 'utf-8');
				echo MarkdownExtra::defaultTransform($htmleArticle);
				if ($row['html_content']) echo $row['html_content'];
			} ?>
				<footer><p>Published on <?php echo $row['created_date']; ?> in <?php echo ucwords($row['category']); ?></p></footer>
				
			</article>
			<?php } ?>
		<?php
		include('../includes/footer.php');
		?>
