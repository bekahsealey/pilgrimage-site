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
	
	// check for article_id in query string
	if (isset($_GET['article_id']) && is_numeric($_GET['article_id'])) {
		$article_id = (int) $_GET['article_id'];
	} else { 
	$article_id = 0;
	}
	$sql = "SELECT title, article, DATE_FORMAT( created,  '%W, %M %e, %Y' ) AS created_date, filename, caption, category FROM php_posts
		LEFT JOIN php_images
		USING (image_id)
		JOIN php_categories
		USING (cat_id)
		WHERE php_posts.article_id = $article_id";
		
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$title_section = "Journal Entry " . " &bull; " . $row['created_date'] . " &bull; " . $row['title'];
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
				$htmleArticle = htmlentities($row['article'], ENT_COMPAT, 'utf-8');
				echo MarkdownExtra::defaultTransform($htmleArticle);
			} ?>
			<p><a href="/journal">Back to the Journal</a></p>
				<footer><p>Published on <?php echo $row['created_date']; ?> in <?php echo ucwords($row['category']); ?></p></footer>
				
			</article>
		<?php
		include('includes/footer.php');
		?>
