<?php
    /**
     * Get the country code for the given IPv6 address from the ipinfodb API.
     * 
     * @param {String:IPv6} ip - The ip to get the country for.
     * @return {String} - The 2 letter country code associated to the given IP.
     */
    
    $ip = @$_POST['ip'];

    // IPinfoDB API for IPv6
    $location = file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=e8dad84ca2cfa796cc01258a5fe5ff9b63765996d7ff53dafaaf652d7b367684&format=json&ip=" . $ip);

    echo $location;
?>
