<?php
    // For internal use
    if(!isset($included))
        include '../login.php';

    // Limit number of stored records for the current domain
    
    // Get domain, POST
    $domain =  $_POST["domain"];
        
    // Get the limit    
    $query   = "SELECT * FROM `ust_limits` 
                WHERE domain = :domain";
                
    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->execute();
    
    $limit = 999999;
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $limit = $row['record_limit'];
    }
    
    // Get clients to be deleted
    $query   = "SELECT DISTINCT token FROM `ust_clients`
                WHERE domain = :domain AND id NOT IN (
                    SELECT id
                    FROM (
                        SELECT id
                        FROM `ust_clients`
                        WHERE domain = :domain2
                        ORDER BY id DESC
                        LIMIT :limit
                    ) foo
                ); ";
    
    $clientsStmt = $db->prepare($query);
    $clientsStmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $clientsStmt->bindValue(':domain2', $domain, PDO::PARAM_STR);
    $clientsStmt->bindValue(':limit', $limit, PDO::PARAM_INT);    
    $clientsStmt->execute();
    
    // Delete the clients and all the related data
    while($row = $clientsStmt->fetch(PDO::FETCH_ASSOC)){
        $_POST['token'] =  $row['token'];
        include "deleteClient.php";
   }
?>