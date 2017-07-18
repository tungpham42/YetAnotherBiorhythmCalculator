<?php
//**** EDIT BELOW ONLY IF YOU KNOW WHAT YOU ARE DOING ****
include('info.inc.php');

$name = $_POST['name'];
$score = $_POST['score'];
$time = $_POST['time'];

$name = preg_replace('/[^a-zA-Z0-9]/', '', $name);
$score = preg_replace('/[^0-9]/', '', $score);
$time = preg_replace('/[^0-9]/', '', $time);

$con = mysql_connect($your_mysql_host, $your_mysql_username, $your_mysql_password);

if(!$con) {
	die("Connection to database failed. Error: ".mysql_error());
}
mysql_select_db($your_db_name, $con);

mysql_query("INSERT INTO ".$your_table_name."(name, score, time) VALUES('$name', '$score', '$time')");

mysql_close($con);
echo "loaded: {".$name.','.$time.','.$score.'}';
?>