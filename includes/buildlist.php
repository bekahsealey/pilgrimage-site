<?php
function buildFileList($dir, $extensions) {
	if (!is_dir($dir) || !is_readable($dir)) {
	return false;
	} else {
		if (is_array($extensions)) {
			$extensions = implode('|', $extensions);
			}
			$pattern = "/\.(?:{$extensions})$/i";
			$folder = new DirectoryIterator($dir);
			$files = new RegexIterator($folder, $pattern);
			$filenames = array();
			foreach ($files as $file) {
						if(strstr($file, 'x-') || strstr($file, '_') ) {
							//don't show these files...
						} else {
				$filenames[] = $file->getFilename();
				}
			}
			natcasesort($filenames);
			return $filenames;
	}
}
?>