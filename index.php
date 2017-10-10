<?php

// Turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Class to load classes it finds the file when the program starts to fail
class Manage {
	public static function autoload($class) {
		include $class . '.php';
	}
}

spl_autoload_register(array('Manage', 'autoload'));

// Instantiate the program object 
$obj = new main();

class main {
	public function __construct() {
		
		// Set default page request
		$pageRequest = 'homepage';
		// Check for parameters in URL
		if(isset($_REQUEST['page'])) {
			// Load the page being requested into pageRequest
			$pageRequest = $_REQUEST['page'];
		}
		// Instantiate the class being requested
		$page = new $pageRequest;

		if($_SERVER['REQUEST_METHOD'] == 'GET') {
			$page->get();
		} else {
			$page->post();
		}
	}
}

abstract class page {
	protected $html;

	public function __construct() {
		// Creates the heading on the webpage
		$this->html .= '<html>';
		$this->html .= '<link rel="stylesheet" href="styles.css">';
		$this->html .= '<body>';
	}

	public function __destruct() {
		// Close out the HTML
		$this->html .= '</body></html>';
		stringFunctions::printThis($this->html);
	}

	public function get() {
		echo 'default get message';
	}

	public function post() {
		print_r($_POST);
	}
}

class homepage extends page {
	public function get() {

	}
}

class uploadForm extends page {
	public function get() {
		$form = '<form action="index.php?uploadForm" method="post">';
		$form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
		$form .= '<input type ="submit" value="Upload File" name="submit">';
		$form .= '</form>';
		$this->html .= '<h1>Upload Form</h1>';
		$this->html .= $form;

		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$fileType = pathinfo($target_file, PATHINFO_EXTENSION);

	}

	public function post() {
		print_r($_FILES);
	}
}

class fileUpload extends page {

}

class htmlTable extends page {}

?>
