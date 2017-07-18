<?php
    include '../login.php';
    
    $domain = $_POST['domain'];
    
    // Get all clients for current domain
    $query = "SELECT id FROM ust_clients WHERE domain = :domain";
    $clientsStmt  = $db->prepare($query);
    $clientsStmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $clientsStmt->execute();

    while($res = $clientsStmt->fetch(PDO::FETCH_ASSOC)) {
        if(!$res)
            return;
        $clientID = $res['id'];

        $query = array();

        // Delete records
        $query[] = "DELETE FROM ust_records WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
        // Delete partial records
        $query[] = "DELETE FROM ust_partials WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
        // Delete movements
        $query[] = "DELETE FROM ust_movements WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
        // Delete clicks
        $query[] = "DELETE FROM ust_clicks WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
        // Delete clientpages
        $query[] = "DELETE FROM ust_clientpage WHERE clientid = :clientid";
        // Delete the client itself
        $query[] = "DELETE FROM ust_clients WHERE id = :clientid";

        foreach($query as $q){
            $stmt = $db->prepare($q);
            $stmt->bindValue(':clientid', $clientID, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
?>
