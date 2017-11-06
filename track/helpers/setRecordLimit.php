<?php
    /*** Set the record limit for a domain ***/
    require_once '../login.php';
    require_once '../permissions.php';
    
    try { checkPermission('SET_RECORD_LIMIT'); } catch(Exception $e) { die($e->getMessage());}
    
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
