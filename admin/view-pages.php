<?php 
	## Set the timezone to your location
	ini_set("date.timezone", "America/Chicago");
	
	require_once('../includes/blog_session_timeout.php');
	require_once('../includes/connection.php');
	//create database connection
	$conn = dbConnect('read');
	
	$sql = 'SELECT * FROM php_pages ORDER BY created DESC';
	$pageResult = $conn->query($sql) or die(mysqli_error());
	//Number of records found
	$pageNumRows = $pageResult->num_rows;
	
	$title_section = "View - All Pages";
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
				
			<p><a href="index.php">Back to Recent</a><a href="new-page.php">Insert new page</a><a href="view-posts.php">View all posts</a></p>
			
			
			<?php 
			//if no records found
			if ($pageNumRows == 0) {
				?>
				<p>No records found</p>
				<?php
			} else {
				?>
				<table>				
					<caption><h4>Pages</h4></caption>
					<thead>
						<tr>
							<th scope="col">Created</th>
							<th scope="col">Title</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php while($row = $pageResult->fetch_assoc()) { ?>
						<tr>
							<td><?php echo $row['created']; ?></td>
							<td><?php echo $row['title']; ?></td>
							<td><a href="_about.php?page_id=<?php echo $row['page_id']; ?>">EDIT</a></td>
							<td>&nbsp;</td>
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
