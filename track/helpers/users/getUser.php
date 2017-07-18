<?php
// getUser.php?level=minLevel
// Check if user is the required level

//For internal use
if(!isset($included)) {
    include '../../login.php';
}

if(isset($_GET['level'])) {
    if($_GET['level'] > $level) {
        die();
    }        
    return;  
}

// getUser.php
// Return row with the user got in login.php
echo json_encode($row);
?>
