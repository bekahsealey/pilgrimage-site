<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
	require_once('../includes/blog_session_timeout.php');
	require_once('../includes/connection.php');
	//create database connection
	$conn = dbConnect('read');
	
	$sql = 'SELECT * FROM php_posts ORDER BY created DESC';
	$result = $conn->query($sql) or die(mysqli_error());
	//Number of records found
	$numRows = $result->num_rows;
	
	$title_section = "View - All Posts";
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
				
			<p><a href="index.php">Back to Recent</a><a href="new.php">Insert new entry</a><a href="view-pages.php">View all pages</a></p>
			
			<?php 
			//if no records found
			if ($numRows == 0) {
				?>
				<p>No records found</p>
				<?php
			} else {
				?>
				
				<table>
					<caption><h4>Posts</h4></caption>
					<thead>
						<tr>
							<th scope="col">Created</th>
							<th scope="col">Title</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php while($row = $result->fetch_assoc()) { ?>
						<tr>
							<td><?php echo $row['created']; ?></td>
							<td><?php echo $row['title']; ?></td>
							<td><a href="_edit.php?article_id=<?php echo $row['article_id']; ?>">EDIT</a></td>
							<td><a href="_delete.php?article_id=<?php echo $row['article_id']; ?>">DELETE</a></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php
			}
			?>
				<footer></footer>
			</article>
		<?php
		include('../includes/footer.php');
		?>
