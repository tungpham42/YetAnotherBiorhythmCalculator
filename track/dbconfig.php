<?php

$host= "127.0.0.1";
$username= "nhipsinh_tracker"; // Mysql username 
$password= "TR@CKv0d0i"; // Mysql password 
$db_name= "nhipsinh_track"; // Database name 

error_reporting(E_ERROR | E_PARSE );

try {
    // Default port, not specified
	$db = new PDO('mysql:host=ss'.$host.';dbname='.$db_name.';charset=utf8', $username , $password,
	              array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
	              );
} catch (PDOException $e) {
    try {
      // Given port (specific 3306 default port if no port is given)
      if(strpos($host, ':')) {
        $port = '3306';
        $host .= ':'.$port;
      }
      
      $host_split =  explode(':', $host);
      $db = new PDO('mysql:host='.$host_split[0].';dbname='.$db_name.';port='.$host_split[1].';charset=utf8', $username , $password,
              array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (PDOException $e) {
        try {
            // Remove any port if it exists, use only IP
            $host = str_replace(':3306','',$host);
            $host = str_replace(':','',$host);
            $db = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username , $password,
                          array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                          );
        } 
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            $db_error = $e;
        } 
    }
}
         
?>