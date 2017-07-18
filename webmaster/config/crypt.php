<?php
/**
* Check phpinfo(); wheter enabled mcrypt extension
*/
return array (
	'key' => 'Your secret key goes here', // your secret key
	'ciphername' => MCRYPT_RIJNDAEL_256, // MCRYPT_ciphername constant. See: http://www.php.net/manual/en/mcrypt.ciphers.php
	'modename' => 'ofb', // ecb, cbc, cfb, ofb, nofb, stream 
	'source' => MCRYPT_DEV_RANDOM, // Use MCRYPT_RAND on Windows platform instead
);