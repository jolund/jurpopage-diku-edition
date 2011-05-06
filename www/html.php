<?php 
/**
 * Gets core libraries and defines some variables
 */
require_once './library/common.php';
require_once './library/configuration.php';

//if(is_array($HTTP_GET_VARS)) extract($HTTP_GET_VARS,EXTR_SKIP);


// Get input variables
// what 'coded' is used for is unknown. Leave it be for now.
if (isset($HTTP_GET_VARS['coded'])) {
	parse_str(decode($coded));
}
$inpid = $HTTP_GET_VARS['id'];
$inpid = intval($inpid);

// db_user,db_passwd and db_name from configuration.php
$mysqli = new mysqli('localhost',$db_user,$db_passwd,$db_name);

if (!$inpid) $inpid = 1; // set default value
$stmt = $mysqli->prepare("SELECT * FROM page WHERE page_id=? limit 1");
$stmt->bind_param('d', $inpid); 
$stmt->execute();
$stmt->bind_result($pg_id, $webpg_title);

$stmt->fetch();
$stmt->close();		// close stmt
$mysqli->close(); 	// close connection to db

// build page stuff

$conn_id = connect();
$web = new speed_template($template_path);
$web->register($template_name);
$web->parse($template_name);
$web_content = $web->return_template($template_name);
disconnect($conn_id);

$active_page_title = "&raquo; ".$webpg_title;
require_once("all_pages.php");
?>