<?php
    //@TODO: This should actually delete the clientPage
    include '../login.php';
    
    $id = $_POST['recordid'];

    // Try deleting the record by id  
    $query = "DELETE FROM ust_records WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // If id is not found in records, try deleting the partial with that id
    if ($stmt->rowCount() == 0) {
        $query = "DELETE FROM ust_partials WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
?>
