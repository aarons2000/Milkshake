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

/* Removed all unnecesarry styles and changed
all IDs with 'messages-*' to 'hwk-*'
to match the new IDs used on the page. */
#hwk-body {
	display: table;
}

#hwk-navbar, #hwk-main-content {
	display: table-row;
}

#hwk-main-content {
	height: 100%;
}

#hwk-preview-pane {
	/*Width roughly 1/6 of page*/
	width: 16%;
	background: #AAA;
	height: 100%;
	
	display: inline-block;
	
	/* Enable scrolling if too small. */
	max-height: 100%;
	overflow-x: hidden;
	overflow-y: scroll;
	
	float: left;
}

#hwk-content-pane {
	width: 84%;
	height: 100%;
	max-height: 100%;
	
	display: inline-block;
	
	/*
	 * Float allows this and messages-preview-pane
	 * to be displayed side-by-side.
	 */
	float: left;
	overflow-y: scroll;
	overflow-x: hidden;
	position: relative;
}

/* Removed style for reply button. */

/* PREVIEW BOX STYLES... */
#hwk-preview-pane ul {
	width: 100%;
	height: auto;
	list-style: none;
	margin: 0;
	padding: 0;
}

#hwk-preview-pane ul li {
	height: 100px;
	width: 100%;
	overflow: hidden;
	border-bottom: 2px #000 solid;
	
	/* Padding INSIDE borders */
	margin: 0;
	padding: 10px 2px;
	box-sizing: border-box;
	cursor: pointer;
}

#hwk-preview-pane ul li h6 {
	text-align: center;
	font-size: 12pt;
	font-weight: 700;
	padding: 0;
	margin: 0;
	color: #000;
	
	/* Wrap words normally */
	word-wrap: normal;
}

#hwk-preview-pane ul li p {
	/* Top padding only */
	padding: 5px 0 0 0;
	
	font-weight: 400;
	color: 333;
}

/* CONTENT BOX STYLES... */
#hwk-content-pane div:nth-of-type(-n+2){
	/* First 2 divs are columns. */
	width: 50%;
	margin: 0;
	
	/* Padding:
	 * Top: 0
	 * Right: 25px
	 * Bottom: 0
	 * Left: 0
	 */
	padding: 0 25px 0 0;
	/* Also use border-box for inner 
	padding instead of outer padding. */
	box-sizing: border-box;
	
	float: left;
	display: inline-block;
}

#hwk-content-pane h1 {
	font-size: 30pt;
	text-align: left;
	
	/* Margin...
	 * Top: 25px,
	 * Right: 0,
	 * Bottom: 5px,
	 * Left: 35px
	 */
	margin: 25px 0 5px 35px;
	
	color: #000;
	
	font-weight: bold;
}

#hwk-content-pane select {
	/* Dropdown box at right. */
	float: right;
}

#hwk-content-pane hr {
	display: block;
	
	/* Add margin to ensure it doesn't
	display under the header block. */
	margin-top: 55px;
}

#hwk-content-pane h2 {
	font-size: 16pt;
	text-align: left;
	margin: 0 0 10px 35px;
	color: #333;
	font-weight: normal;
}

#hwk-content {
	/* Set up so only the content scrolls
	with an overflow, rather than the whole page. */
	position: absolute;
	bottom: 0;
	top: 125px;
	padding: 10px 35px;
}

/* SPECIAL STYLES... */
.hwk-preview-selected {
	background: #BFBFBF;
}

.hwk-overdue {
	/* Important colour style
	for overdue tasks. */
	color: red !important;
}

.hwk-complete {
	/* Same as above but for
	complete tasks. */
	color: green !important;
}

/* Removed all styles relating
to the message reply section. */