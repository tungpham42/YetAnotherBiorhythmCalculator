<?php
    // Safety first
    include '../../login.php';
   
    // Get domain, POST
    $domain = @$_POST["domain"]; 

    // Visitors in last 24h
    $query = "SELECT COUNT(*) as count
              FROM `ust_clientpage` AS t1
                INNER JOIN `ust_clients` AS t2 ON t1.clientid = t2.id
                WHERE t2.domain = :domain AND t1.date >= now() - INTERVAL 1 DAY
              GROUP BY t1.clientid";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->execute();

    $data = array();
    $result = $stmt->rowCount();

    $data['visitors24h'] = $result | 0;

    // Visitors online
    $query = "SELECT COUNT(*) as count
              FROM `ust_clientpage` AS t1
                INNER JOIN `ust_clients` AS t2 ON t1.clientid = t2.id
                WHERE t2.domain = :domain AND t1.last_activity >= now() - INTERVAL 5 SECOND
              GROUP BY t1.clientid";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->rowCount();

    $data['visitorsOnline'] = $result | 0;

    // Top referrers
    $query = "SELECT COUNT(*) as count, referrer as url from `ust_clients`
              WHERE domain = :domain
              GROUP BY referrer
              ORDER BY count DESC
              LIMIT 5";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data['referrers'] = $result;

    echo json_encode($data);
 ?>