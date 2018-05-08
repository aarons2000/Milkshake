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

/*Define style for columns at 1/3 width.*/
#index-content-columns > * {
	vertical-align: top; /*Align children (columns) at top of container.*/
}

.index-content-column {
	width: 33%;
	max-width: 33%;
	float: left;
	margin: 0;
	/*Margin must be 0 to stop overflow, since 3 columns will take 100% of the page width.*/
	
	display: inline-block;
	/*
	 * Inline block to allow block formatting where the columns are next to each other
	 * instead of being on below another.
	 */
	 
	 align-content: center;
	 text-align: center;
	 /*Align content in center of columns, as per my design.*/
	 
	 padding: 0 15px; /*Add padding to the left and right sides of the column.*/
	 box-sizing: border-box; /*Stop padding from adding extra width.*/
	 
	 overflow: hidden;
}

/*Style for all direct children of the columns.*/
.index-content-column > * {
	display: block;
	width: 100%;
	margin: 20px 0 0 0; /*Add 20px margin to the top to move the elements further down the page.*/
	
	align-content: center;
}

.index-content-column ul {
	width: 100%;
	position: relative;
	overflow: auto;
	box-sizing: border-box;
	
	margin-left: -20px;
	
	font-size: 20px;
}

.index-content-column ul li {
	margin-top: 5px;
}

.index-column-header {
	color: #000;
}