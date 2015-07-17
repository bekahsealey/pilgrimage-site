			<?php 
				$pages = buildFileList($list_dir, array($file_type)); 
				//print_r($pages);
				foreach ($pages as $page) {
					$page_name = $page;
					$search = array($prefix, $extension, '-');
					$replace = array('', '', ' ');
					$page_name = str_replace($search, $replace, $page_name);
					$page_name = ucwords($page_name);
					echo "<li><a href=\"$page\">" . $page_name . "</a></li>";
				}
			?>