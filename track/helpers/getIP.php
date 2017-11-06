<?php
    // Returns the IP of the current user, the value is wrapped as a JS asignment to the ust_myIP variable
    
    $remote_addr = $_SERVER['REMOTE_ADDR'];
        
    // Check for HTTP_X_FORWARDED_FOR HEADER
    // THANKS TO: victorbaptista for this fix :)
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $tmpip = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        $remote_addr = trim($tmpip[0]);
    }

    // Fix for IPv6 ::1 (localhost)
    if($remote_addr == '::1') $remote_addr = '127.0.0.1';

    echo "ust_myIP='$remote_addr';";
?>
