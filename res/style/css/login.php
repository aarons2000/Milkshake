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

#login-section {
	align-content: center;
	align-items: center;
	text-align: center;
}

/*Add margin at bottom except last direct child.*/
#login-form > input:not(:last-child) {
	margin-bottom: 25px;
}

#login-form {
	margin: auto;
}

#login-error-section {
	padding: 0 auto;
	margin: 0 auto 25px auto;
}