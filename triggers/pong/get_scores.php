<?php
//**** EDIT BELOW ONLY IF YOU KNOW WHAT YOU ARE DOING ****
include('info.inc.php');

$con = mysql_connect($your_mysql_host, $your_mysql_username, $your_mysql_password);

if(!$con) {
	die("Connection to database failed. Error: ".mysql_error());
}

mysql_select_db($your_db_name, $con);

$res = mysql_query("SELECT name, score, time FROM ".$your_table_name." ORDER BY score DESC, time ASC LIMIT 0, 10");
$data = '{';
$rank = 0;
while($row = mysql_fetch_array($res))
{
	$data .= '"rank'.$rank.'":';
	$data .= '{"name": "'.$row['name'].'","score": "';
	$data .= $row['score'].'","time": "';
	$data .= $row['time'].'"},';
	$rank++;
}
$data = substr($data, 0, strlen($data)-1).'}';

echo json_encode($data);
mysql_close($con);

?>