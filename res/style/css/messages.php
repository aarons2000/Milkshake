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

#messages-body {
	display: table;
}

#messages-navbar, #messages-main-content {
	display: table-row;
}

#messages-main-content {
	height: 100%;
}

#messages-preview-pane {
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

#messages-content-pane {
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

#messages-reply-btn {
	position: absolute;
	bottom: 25px;
	right: 25px;
}

/* PREVIEW BOX STYLES... */
#messages-preview-pane ul {
	width: 100%;
	height: auto;
	list-style: none;
	margin: 0;
	padding: 0;
}

#messages-preview-pane ul li {
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

#messages-preview-pane ul li h6 {
	text-align: center;
	font-size: 12pt;
	font-weight: 700;
	padding: 0;
	margin: 0;
	color: #000;
	
	/* Wrap words normally */
	word-wrap: normal;
}

#messages-preview-pane ul li p {
	/* Top padding only */
	padding: 5px 0 0 0;
	
	font-weight: 400;
	color: 333;
}

/* CONTENT BOX STYLES... */
#messages-content-pane h1 {
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

#messages-content-pane h2 {
	font-size: 16pt;
	text-align: left;
	margin: 0 0 10px 35px;
	color: #333;
	font-weight: normal;
}

#messages-content {
	/* Set up so only the content scrolls
	with an overflow, rather than the whole page. */
	position: absolute;
	bottom: 0;
	top: 125px;
	padding: 10px 35px;
}

/* SPECIAL STYLES... */
.messages-preview-selected {
	background: #BFBFBF;
}

#messages-reply {
	/* Center the element in the page. */
	position: absolute;
	
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	
	margin: auto;
	
	/* Display above everything. */
	z-index: 1000;
	
	/* Set maximum dimensions */
	max-width: 50%;
	max-height: 60%;
	
	background: #FFF;
	
	/* Add a rounded border... */
	border: rgb(255, 102, 0) solid 2px;
	border-radius: 5px;
	
	/* Align content in middle... */
	align-content: center;
	
	/* Hide the section at start... */
	visibility: hidden;
}

#messages-reply label {
	display: table-cell;
	
	/* Center horizontally */
	left: 0;
	right: 0;
	margin: 0 auto;
	padding: 15px;
	box-sizing: border-box;
}

/* First divider in element... */
#messages-reply div {
	margin: 0;
	
	display: inline-block;
	float: left;
}

#messages-reply div:nth-child(1) {
	/* Column 30% of width of element. */
	height: 100%;
	width: 30%;
	padding: 10px 0 0 0;
}

#messages-reply div:nth-child(2) {
	/* Column 30% of width of element. */
	height: 100%;
	width: 70%;
}

#messages-reply div:nth-child(2) label {
	padding: 20px 0 0 0;
}

/* Align things */
#messages-reply div * {
	margin-top: 15px;
}

#messages-reply label {
	display: block;
	margin: 0;
}

#messages-reply div input, textarea {
	width: 90%;
}