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

.home-list-seperator{
	width: 100%;
	display: block;
	padding-bottom: 50px;
	
	/*
	 * Max height of 300px, 
	 * if exceeded scrolling will be enabled...
	 */
	max-height: 300px;
	overflow-y:  scroll;
}