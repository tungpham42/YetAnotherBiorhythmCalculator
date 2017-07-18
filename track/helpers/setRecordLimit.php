<?php
    /*** Set the record limit for a domain ***/
    require_once '../login.php';

    //Check permission
    $_GET['level'] = 5;
    $included = 1;
    include 'users/getUser.php';
    
    //Get domain, POST
    $domain =  $_POST["domain"];
    $limit  = $_POST["limit"];
    
    //Get the limit    
    $query   = "INSERT INTO `ust_limits` (domain, record_limit) VALUES (:domain, :limit)
                ON DUPLICATE KEY UPDATE record_limit = VALUES(record_limit)";
                
    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();  
?>
