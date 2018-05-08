<?php
namespace milkshake;
if (empty($_SESSION)){
	session_start();
}

//Get the directory of this script, this will help to locate the config file.
$dir = dirname(__FILE__);

/*
 * Get the config paramaters from the config file. The file uses the JSON format so I use json_decode()
 * to get the data from the file.
 * file_get_contents() opens a file and reads it, returning a string of text from the file it reads.
 * The second parameter (true) means that it will return an associative array of values.
 */
$cfg = json_decode(file_get_contents("{$dir}/config.json"), true);

//Define a case-insensitive constant for database-related values.
define("DATABASE", $cfg['database'], true);

//Get root directory and root URL, removing trailing slashes.
$rootUrl = rtrim($cfg['site']['rootUrl'], "/");
$rootUrl = rtrim($rootUrl, "\\");

$rootDir = rtrim($cfg['site']['rootDir'], "/");
$rootDir = rtrim($rootDir, "\\");

define("ROOT_URL", $rootUrl, true);
define("ROOT_DIR", $rootDir, true);

/* 
 * Use the glob function to retreive all files in the current directory.
 * The string used in this function is a similar to regex string, so if I wanted to retreive all
 * PHP, HTML and CSS files in this directory, I could by changing "*.php" to "*.{php,html,css}".
 * I would also have to use the flag "GLOB_BRACE" to signal that I am using a regex-like string.
 */
$files = glob("{$dir}/*.php");

foreach($files as $file){
	if ($file != basename(__FILE__, ".php")){
		//Use include_once() in case the script has already been included.
		include_once($file);
	}
}

//Include jQuery and jQuery UI
print("<script src=\"" . ROOT_URL . "/scripts/jquery/jquery.min.js\" type=\"text/javascript\"></script>");

//Include Transit jQuery library.
print("<script src=\"" . ROOT_URL . "/scripts/jquery/jquery.transit.min.js\" type=\"text/javascript\"></script>");

/* Include main CSS file that will be used on all pages.
 * Using the root URL because the file is being included client-side
 * so it needs to be referenced by a URL instead of a directory.
 */
print("<link rel=\"stylesheet\" type=\"text/css\" href=\"" . ROOT_URL . "/res/style/css/style.php\" />");

//The following meta tags apply to all pages.
print("<meta charset=\"UTF-8\" />");
print("<meta name=\"author\" content=\"Aaron Sharp\" />");