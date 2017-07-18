<?php
    include '../login.php';
   
    // Get all domains that this user has access to
    $query = "SELECT domain FROM ust_access WHERE userid = :userid";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Get the number of visitors for each of these domains
    $res = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    
        $query2 = "SELECT COUNT(*) as cnt FROM ust_clients WHERE domain = :domain";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindValue(':domain', $row['domain'], PDO::PARAM_STR);
        $stmt2->execute();

        $res2   = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        $res[] = array('domain' => $row['domain'], 'count' => $res2['cnt']);
    };

  echo json_encode($res);
?>
