<?php
    require_once '../login.php';
  
    $token = $_POST['token'];

    // Get clientID from token
    $query = "SELECT id FROM ust_clients WHERE token = :token";
    $stmt  = $db->prepare($query);
    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    $res = $stmt->fetch(PDO::FETCH_ASSOC);
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
    // Delete tags
    $query[] = "DELETE FROM ust_client_tag WHERE clientid = :clientid";
    // Delete the client itself
    $query[] = "DELETE FROM ust_clients WHERE id = :clientid";

    foreach($query as $q){
        $stmt = $db->prepare($q);
        $stmt->bindValue(':clientid', $clientID, PDO::PARAM_INT);
        $stmt->execute();
    }   
?>