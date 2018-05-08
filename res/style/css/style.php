<?php
/* 
 * Use a header to say that this file contains CSS,
 * not PHP / HTML.
 * Without using this header, browsers will assume that
 * this file contains HTML, pre-processed by PHP.
 */
header("Content-type: text/css; charset: UTF-8");

/*
 * Include script containing constants in case it
 * hasn't already been included.
 * Use ob_start() and ob_end_clean() to supress anything
 * the inclusion script echoes.
 */
ob_start();
include_once "../../../scripts/inc_scripts.php";
ob_end_clean();
?>

/*<style>*/

/* Define Orbitron font, which will be used for the header text. */
@font-face {
	font-family: Orbitron;
	src: url("<?php echo ROOT_URL; ?>/res/style/fonts/orbitron-medium.otf");
}

html, body {
	background: #FFF;
	font-family: Arial, sans-serif;
	margin: 0;
	padding: 0;
	width: 100%;
	height: 100%;
}

#navbar {
	font-family: Orbitron;
	position: static;
	width: 100%;
	height: 75px;
	
	margin: 0;
	
	background: rgb(255, 102, 0);
}

#navbar-logo {
	position: absolute;
	width: 300px;
	top: 5px;
	vertical-align: middle;
}

/*Headers*/
h1, h2, h3, h4, h5, h6 {
	font-family: Arial, sans-serif;
	color: rgb(255, 102, 0);
}

h1, h2 {
	text-align: center;
}

h1 {
	font-size: 40px;
	font-weight: 900;
}

h2 {
	font-size: 37px;
	font-weight: 800;
}

h3 {
	font-size: 32px;
	font-weight: 700;
}

h4 {
	font-size: 30px;
	font-weight: 600;
}

h5, h6 {
	font-size: 25px;
}

h5 {
	font-weight: 500;
}

h6 {
	font-weight: normal;
}

/*Style all list elements for navbar menu.*/
.navbar-menu {
	position: relative;
	display: inline-block;
	float: right; /*Makes menu right-tol-left.*/
	padding-right: 10px; /*Move slightly away from the far right of the screen.*/
}

.navbar-menu-btn {
	background: rgba(255, 102, 0);
	
	color: #FFF;
	font-weight: bold;
	
	padding: 0 16px;
	border: none;
	text-decoration: none; /*Removes underline.*/
	
	display: inline-block;
	height: 75px;
	line-height: 75px;
	min-width: 100px;
	text-align: center;
	margin: 0;
}

.navbar-dropdown {
	display: inline-block;
}

.navbar-menu-btn:hover {
	background: rgb(175, 70, 0); /*Highlight link when it is hovered over.*/
}

.navbar-dropdown-content {
	display: none; /*Hidden unless the button is hovered over.*/
	position: absolute;
	
	background: rgb(255, 102, 0);
}

.navbar-dropdown-content a {
	color: #FFF;
	padding: 12px 16px;
	text-decoration: none;
	display: block;
	min-width: 100px;
	width: inherit; /*Make width equal to its parents width.*/
}

.navbar-dropdown-content a:hover {
	background: rgb(175, 70, 0); /*Highlight link when it is hovered over.*/
}

.navbar-dropdown:hover .navbar-dropdown-content {
	display: block; /*Display dropdown content when button is hovered over.*/
}

/*Text area and fields design.*/
input[type="text"],
input[type="tel"],
input[type="password"],
textarea {
	display: inline-block;
	background: rgb(200, 200, 200);
	
	border-bottom: 5px solid rgb(255, 102, 0) !important;
	border: 0;
	border-radius: 5px;
	
	margin: 8px 0;
	box-sizing: border-box;
	
	font-size: 12pt;
	padding: 7px 12px;
}

/* Styling all input buttons and <button> tage */
input[type="submit"],
input[type="button"],
button {
	font-size: 16pt;
	font-weight: bold;
	color: #FFF;
	background: rgb(255, 102, 0);
	border: none;
	padding: 5px 10px;
	border-radius: 50px;
	cursor: pointer;
	
	transition: background 200ms ease-in-out;
}

input[type="submit"]:hover,
input[type="button"]:hover,
button:hover {
	background: rgb(175, 70, 0); /*Highlight button when it is hovered over.*/
}

label {
	font-size: 12pt;
}

.block-br {
	display: block;
}

.error {
	color: rgb(200, 0, 0);
}

.url-no-change {
	color: #00F;
	text-decoration: none;
}