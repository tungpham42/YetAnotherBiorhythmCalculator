<?php

    require_once '../login.php';

    // Level 3 required to remove tags
    if($level < 3)
        die("Your user level is not high enough to remove tags! Level 3 is required");

    // Here's the argument from the client.
    $clientID     = $_POST['clientID'];
    $tagContent   = $_POST['tagContent'];

    $query = "DELETE FROM `ust_client_tag` WHERE clientid = :clientID AND tag = :tagContent";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':clientID', $clientID, PDO::PARAM_INT);
        $stmt->bindValue(':tagContent', $tagContent, PDO::PARAM_STR);
        $stmt->execute();
?>
