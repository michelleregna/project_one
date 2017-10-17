<?php

// Turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Load classes it finds the file when the program starts to fail
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
		$pageRequest = 'uploadForm';
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
		$this->html .= '<link rel="stylesheet" href="style.css">';
		$this->html .= '<body>';
	}

	public function __destruct() {
		// Close out the HTML
		$this->html .= '</body></html>';
		stringFunctions::printThis($this->html);
	}
}

class uploadForm extends page {
	public function get() {
		// Create HTML form
		$form = '<form enctype="multipart/form-data" action="upload.php" method="post">';
		$form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
		$form .= '<input type ="submit" value="Upload File" name="submit">';
		$form .= '</form>';
		$this->html .= '<h1>Upload Form</h1>';
		$this->html .= $form;
	}
}

class htmlTable extends page {
	public function get() {
		// Get filename from URL
		$fileName = $_GET['fileName'];
		$file = fopen("/afs/cad.njit.edu/u/m/c/mcr35/public_html/project_one/uploads/".$fileName,"r");
		
		// Create HTML table
		$table = "<table>";
		while (($line = fgetcsv($file)) !== false) {
        			$table .= "<tr>";
        	foreach ($line as $cell) {
                $table .= "<td>" . htmlspecialchars($cell) . "</td>";
        	}
        	
        	$table .= "</tr>\n";
		}
	
		fclose($file);
		$table .= "</table>";
		$this->html .= $table;
	}
}

?>
