<?php

    include 'dbconfig.php';

    // Cross-domain request
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type');

    // Here's the argument from the client.
    $clientPageID = $_POST['clientPageID'];
    $what         = $_POST['what'];
    $json         = null;

    // SQL injection prevention
    $allowedColumns = array('data', 'record');
    if(!in_array($what, $allowedColumns)) {
        die('Datatype invalid: '. $what);
    }

    // Update time of latest known activity
    $query = "UPDATE `ust_clientpage` SET last_activity = NOW() WHERE id = :clientPageID";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
    $stmt->execute();


    // If we have a full record, add it
    if($what == 'record') {
        $json = $_POST['record'];

        try {
            $query = "INSERT INTO `ust_records` (client, record) VALUES (:clientPageID, :json)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
            $stmt->bindValue(':json', $json, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (PDOException $e) {
            die("Could not insert full record into database.\n" . $query);
        }

        die();
    }


    if($what == 'data') {
        // Get the data from POST
        $movements = $_POST['movements'];
        $clicks = $_POST['clicks'];
        $partial = $_POST['partial'];

        // Add the partial
        if($partial != '') {
            try {
                $query = "INSERT INTO `ust_partials` (client, record) VALUES (:clientPageID, :json)
                          ON DUPLICATE KEY UPDATE record = :json2";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
                $stmt->bindValue(':json', $partial, PDO::PARAM_STR);
                $stmt->bindValue(':json2', $partial, PDO::PARAM_STR);
                $stmt->execute();
            }
            catch (PDOException $e) {
                echo "Could not insert partial record into database.\n" . $query;
            }
        }

        // Add the movements
        if($movements != '') {
            try {
                $query = "INSERT INTO `ust_movements` (client, data) VALUES (:clientPageID, :json)";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
                $stmt->bindValue(':json', $movements, PDO::PARAM_STR);
                $stmt->execute();
            }
            catch (PDOException $e) {
                echo "Could not insert data into database.\n" . $query;
            }
        }

        // Add the clicks
        if($clicks != '') {
            try {
                $query = "INSERT INTO `ust_clicks` (client, data) VALUES (:clientPageID, :json)";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
                $stmt->bindValue(':json', $clicks, PDO::PARAM_STR);
                $stmt->execute();
            }
            catch (PDOException $e) {
                echo "Could not insert data into database.\n" . $query;
            }
        }

        die();
    }

    echo 'Not supported statistics type '.$what;
?>
