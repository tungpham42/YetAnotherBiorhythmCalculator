<?php
    require_once '../login.php';

    $query = "SELECT TIMESTAMPDIFF(MINUTE, UTC_TIMESTAMP, NOW())";
    $stmt = $db->prepare($query);
    $stmt->execute();

    echo $stmt->fetchColumn();
?>