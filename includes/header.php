<!doctype html>
<html class="no-js">
<head>
	<meta charset="UTF-8">
	<title>Your Website | <?php if(isset($title_section)) echo "$title_section"; ?></title>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/SlickNav/1.0.4/jquery.slicknav.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/SlickNav/1.0.4/slicknav.min.css" media="screen">
	<link rel="stylesheet" href="/css/style.css" media="screen">
	<link href='http://fonts.googleapis.com/css?family=Amaranth' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="/css/images/favicon.ico">
</head>

<body class="<?php 
	$id = $currentPage; 
	echo "$id";
	?>">
	<?php include_once('analyticstracking.php'); ?>
	<a class="mobile-nav" href="/index.php"><img id="mobile-title" src="/css/images/title.png" alt="home"></a>
	<div id="wrapper">
		<header id="page-header">
			<h1>Title</h1>
			<h2>Tagline</h2>
		</header>
		<nav id="main-nav">
			<ul class="main-menu">
				<li <?php if ($currentPage == 'home') {echo 'id="current"';} ?>><a href="/">Home</a></li>
				<li <?php if ($currentPage == 'about-us') {echo 'id="current"';} ?>><a href="/about-us/">About Us</a></li>
				<li <?php if ($currentPage == 'media') {echo 'id="current"';} ?>><a href="/media/">Media</a></li>
				<li <?php if ($currentPage == 'schedule') {echo 'id="current"';} ?>><a href="/schedule/">Events</a></li>
				<li <?php if ($currentPage == 'journal' || $currentPage == 'entry') {echo 'id="current"';} ?>><a href="/journal/">Journal</a></li>
				<li <?php if ($currentPage == 'contact-us') {echo 'id="current"';} ?>><a href="/contact-us/">Contact Us</a></li>
				<?php if ($currentPage == 'admin') { ?> <li <?php echo 'id="current"'; ?>> <a href="/admin/">Admin</a>
					<ul>
						<?php 
						include('list-files.php');
						?>
					</ul>
				</li>
				<?php } ?>
			</ul>
			<?php if(isset($_SESSION['authenticated'])) include('blog_logout.php'); ?>
		</nav>
			
		<div id="main-content" class="grid-pad">
			<h1 id="page-title"><?php echo $title_page_name; ?></h1>
			<?php if ($currentPage == 'home') { ?>
			<div id="video" class="">
				<?php
				if ($randRow && !empty($randRow['filename'])) {
					$filename = "/videos/{$randRow['filename']}";
					$poster = "/videos/posters/{$randRow['postername']}";
				 ?>
					<video class="absolute-center" preload controls poster="<?php echo $poster; ?>" title="<?php echo $randRow['title']; ?>">
						<source src="<?php echo $filename; ?>.mp4" type="video/mp4" class="mobile">
						<source src="<?php echo $filename; ?>.mp4" type="video/mp4" class="standard">
						<source src="<?php echo $filename; ?>.ogv" type="video/ogg">
						<source src="<?php echo $filename; ?>.webm" type="video/webm">
					<iframe class="absolute-center home" width="720" height="405" src="//www.youtube.com/embed/DriCVkaIL8c?rel=0" allowfullscreen title="Video Title"></iframe>	
					</video>
					<?php } ?>
			</div>
			<?php } ?>
		
		<?php if ($currentPage != 'admin') {
			include('sidebar.php');
		}
		?>
			
		<section class="col-1-2">
		