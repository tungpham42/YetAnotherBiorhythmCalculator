<?php

    require_once '../login.php';
    require_once '../permissions.php';
    
    try { checkPermission('REMOVE_TAG'); } catch(Exception $e) { die($e->getMessage());}

    // Here's the argument from the client.
    $clientID     = $_POST['clientID'];
    $tagContent   = $_POST['tagContent'];

    $query = "DELETE FROM `ust_client_tag` WHERE clientid = :clientID AND tag = :tagContent";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':clientID', $clientID, PDO::PARAM_INT);
        $stmt->bindValue(':tagContent', $tagContent, PDO::PARAM_STR);
        $stmt->execute();
?>
