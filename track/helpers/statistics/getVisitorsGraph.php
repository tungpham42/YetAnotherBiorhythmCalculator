<?php

    // Safety first
    include '../../login.php';
   
       // Get domain, POST
    $domain =  $_POST["domain"]; 

    $query = "SELECT clientDate, COUNT(*)
              FROM (
                    SELECT DATE(t1.date) as clientDate, t2.id
                      FROM `ust_clientpage` AS t1
                        INNER JOIN `ust_clients` AS t2 ON t1.clientid = t2.id
                        WHERE t2.domain = :domain
                      GROUP BY t1.clientid
                      ORDER BY t1.date ASC
                ) as t3 
              GROUP BY clientDate";

      $stmt = $db->prepare($query);
      $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
      $stmt->execute();

      $result = $stmt->fetchAll();

    echo json_encode($result);
 ?>