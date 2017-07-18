<?php
define('MYSQL_CONNECT_ERROR', "Can't connect to database. Check host, username and password.");
define('DB_CREATE_ERROR', "Can't create database.");
define('DB_SELECT_ERROR', "Can't select database. Check database name maybe it does not exist.");
define('TBL_CREATE_ERROR', "Can't create table.");
define('FILE_CREATE_ERROR', "Can't create configuration file. Check read/write permission.");

$host = $_POST['host'];
$user = $_POST['user'];
$pass = $_POST['pass'];
$name = $_POST['name'];
$pref = $_POST['pref'];
$new  = $_POST['new'];

$con = @mysql_connect($host, $user, $pass);

if(!$con) {
	die(MYSQL_CONNECT_ERROR);
}

if(strtolower($new) == 'true') {
	if(!mysql_query("CREATE DATABASE ".$name)) {
		die(DB_CREATE_ERROR);
	}else { 
		if(!mysql_select_db($name, $con))
			die(DB_SELECT_ERROR);
	}
} else if(strtolower($new) == 'false') {
	if(!mysql_select_db($name, $con))
		die(DB_SELECT_ERROR);
}

$query = "CREATE TABLE ".$pref."html5pong
(
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
name varchar(5),
score int,
time int
)";


$f = 'info.inc.php';

if(!mysql_query($query, $con)) {
	die(TBL_CREATE_ERROR);
}else {
	for($i=0; $i< 10; ++$i) {
		$dataq = "INSERT INTO ".$pref."html5pong
					(id, name, score, time)
					VALUES (NULL ,  'NOONE',  '0',  '0');";
		if(mysql_query($dataq, $con)) continue; else return;
	}

	if(file_exists($f)) {
		if(!is_writable($f)) {
			die(FILE_CREATE_ERROR);
		}
	}
	
	$file = @fopen($f, 'w+');
	if($file === false) die(FILE_CREATE_ERROR);
	fwrite($file, "<?php
	//**** EDIT HERE ONLY ****
	\$your_mysql_host = '$host'; // The name of your MySQL host (usually this is 'localhost').
	\$your_mysql_username = '$user'; // The username used for your MySQL connection.
	\$your_mysql_password = '$pass'; // The password for the MySQL user specified above.
	\$your_db_name = '$name'; // The name of the database you want to use. The database MUST exist in your MySQL setup.
	\$your_table_name = '{$pref}html5pong'; // The name of the table created for the game. The table MUST exist in the database specified above.
	//************************
	?>");

	fclose($file);
	echo "SUCCESS";
}

mysql_close($con);

?>