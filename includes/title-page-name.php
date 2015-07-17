<?php
//This script dynamically sets the page heading depending on the page being viewed.
	$currentPath = getcwd();
	$d = explode('/', $currentPath);
	$currentPage = end($d);
	$sitedir = 'site directory';//update site directory to the server folder name which holds your website files to detect the home page
	$bull = '&bull;';
	$site = 'This site'; //Update this with your desired display name
	if ($currentPage == $sitedir  && $title_section == 'Home') { 
		$currentPage = 'home';
		$title_page_name = $site;
	} elseif ($currentPage == $sitedir && $title_section != 'Home') {
		$currentPage = 'entry'; 
		$title_page_name = str_replace('-', ' ', $currentPage);
		$title_page_name = ucwords($title_page_name);
		$title_page_name = $title_page_name . ' ' . $bull . ' ' . $site;
	} elseif ($currentPage != $sitedir) {
		$title_page_name = str_replace('-', ' ', $currentPage);
		$title_page_name = ucwords($title_page_name);
		$title_page_name = $title_page_name . ' ' . $bull . ' ' . $site;
	} else {
		$title_page_name = $site;
	}
?>