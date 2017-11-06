<?php
    // Only allow data retrieval for logged in users
    // Excepting when a directPlayback is requested.
    if(!isset($_POST['directPlayback'])) {
        include '../../login.php';
    } else {
        if(!isset($_POST['key']) || !isset($_POST['clientid']))
            die('Invalid public key.');

        // Direct playback, verify recording key
        include '../../dbconfig.php';

        $query = "SELECT public_key FROM ust_clients
                  WHERE id = :clientID";
            
        $stmt = $db->prepare($query);
        $stmt->bindValue(":clientID", $_POST['clientid'], PDO::PARAM_INT);
        $stmt->execute();

        if($_POST['key'] !== $stmt->fetchColumn())
            die('Invalid public key.');
    }

    $clientPageID = $_POST['clientPageID'];

    // TODO: Get record data based on clientPageID
    $query = "SELECT * FROM ust_records 
                WHERE client = :clientPageID";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(":clientPageID", $clientPageID, PDO::PARAM_INT);
    $stmt->execute();
    
    // Maybe it is a partial recording
    if($stmt->rowCount() != 1){
        $query = "SELECT * FROM ust_partials 
                    WHERE client = :clientPageID";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(":clientPageID", $clientPageID, PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt->rowCount() != 1)
            die("Could not find record for client #$clientPageID in database!");
    }
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Remove "\" from the beggining, if for some reason exists
    $json = preg_replace("{\\\}","",$row['record']);
    
    echo $json;
?>
