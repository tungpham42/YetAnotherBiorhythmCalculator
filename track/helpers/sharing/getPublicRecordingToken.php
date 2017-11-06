<?php
    // Returns the public token for the given clientID
    // If it is null, generate a new random public token and return it

    require_once '../../login.php';
    require_once '../../permissions.php';
    try { checkPermission('SHARE_RECORDING'); } catch(Exception $e) { die($e->getMessage());}

    // Make sure user has access to this domain
    // @TODO!!

    // Here's the argument from the client.
    $clientID     = $_POST['clientID'];

    $query = "SELECT public_key FROM `ust_clients` WHERE id = :clientID";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':clientID', $clientID, PDO::PARAM_INT);
        $stmt->execute();

    $res = $stmt->fetchColumn();

    if(!$res) {
        // Generate random token
        $res = substr(str_shuffle(MD5(microtime())), 0, 12);

        // Save the token
        $query = "UPDATE`ust_clients` SET `public_key` = :key WHERE id = :clientID";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':clientID', $clientID, PDO::PARAM_INT);
            $stmt->bindValue(':key', $res, PDO::PARAM_STR);
            $stmt->execute();
    }

    echo $res;
?>
