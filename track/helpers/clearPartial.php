<?php
    include '../dbconfig.php';
    
    // Cross-domain request
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type');

    $clientPageID = $_POST['clientPageID'];

    // Delete partial records
    $query = "DELETE FROM ust_partials WHERE client = :clientPageID";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_STR);
    $stmt->execute();
?>
