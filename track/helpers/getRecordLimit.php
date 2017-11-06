<?php
    /*** Get the record limit for a domain ***/
    require_once '../login.php';
    
    //Get domain, POST
    $domain =  $_POST["domain"];
    
    //Get the limit    
    $query   = "SELECT record_limit FROM `ust_limits` WHERE domain = :domain";
                
    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->execute();  
    
    if($stmt->rowCount() == 1){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        echo $row['record_limit'];
    }
    
    else echo '1000';   
    
?>
