<?php
require_once('Upload.php');
require_once('Thumbnail.php');

class Ps2_ThumbnailUpload extends Ps2_Upload {
	// keyword extends allows this class to inherit all of the properties and methods of the named class
  
  // note: the named class file was included: Upload.php
  
  // any methods written in this class 
  // that have the same name as a method in the parent class
  // will override the parent's method
  
  protected $_thumbDestination;
  protected $_deleteOriginal;
  protected $_suffix = '_thumb';
  
  public function __construct($path, $deleteOriginal = false) {
	  parent::__construct($path); //The Scope Resolution Operator (also called Paamayim Nekudotayim)
	  $this->_thumbDestination = $path;
	  $this->_deleteOriginal = $deleteOriginal;
  }
  
  public function setThumbDestination($path) {
	  if (!is_dir($path) || !is_writable($path)) {
		  throw new Exception("$path must be a valid, writable directory.");
	  }
	  $this->_thumbDestination = $path;
  }
  
  public function setThumbSuffix($suffix) {
	  if (preg_match('/\w+/', $suffix)) {
		  if (strpos($suffix, '_') !== 0) {
			  $this->_suffix = '_' . $suffix;
		  } else {
			  $this->_suffix = '_';
		  }
	  } else {
		  $this->_suffix = '';
	  }
  }
  
  protected function createThumbnail($image) {
	  $thumb = new Ps2_Thumbnail($image);
	  $thumb->setDestination($this->_thumbDestination);
	  $thumb->setSuffix($this->_suffix);
	  $thumb->setMaxSize(116);
	  $thumb->create();
	  $messages = $thumb->getMessages();
	  $this->_messages = array_merge($this->_messages, $messages);
  }
  
  ## Updated processFile overrides the Method in the Parent Class
  protected function processFile($filename, $error, $size, $type, $tmp_name, $overwrite) {
	$OK = $this->checkError($filename, $error);
	if ($OK) {
	  $sizeOK = $this->checkSize($filename, $size);
	  $typeOK = $this->checkType($filename, $type);
	  if ($sizeOK && $typeOK) {
		$name = $this->checkName($filename, $overwrite);
		$success = move_uploaded_file($tmp_name, $this->_destination . $name);
		if ($success) {
		  // add the amended filename to the array of filenames
		  $this->_filenames[] = $name;
		  // don't add a message if the original image is deleted
		  // updated code...
		 if (!$this->_deleteOriginal) {
			 $message = "$filename uploaded successfully";
			 if ($this->_renamed) {
				 $message .= " and renamed $name";
			 }
			 $this->_messages[] = $message;
		}
		//create a thumbnail from the uploaded image
		//call the createThumbnail Method that will create: new Ps2_Thumbnail($image);
		$this->createThumbnail($this->_destination . $name);
		//delete the uploaded image if required
		if ($this->_deleteOriginal) {
			unlink($this->_destination . $name);
		}
		} else {
			$this->_messages[] = "Could not upload $filename";
		}
	  }
	}
  }
  
}