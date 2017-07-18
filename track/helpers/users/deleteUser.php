<?php
    /**
     * Removes an user and all it's domain accesses
     * @HTTP POST
     * @param {Int} id - userId to delete
     * @param {String} name - userName to  delete, if id is not given
     */
    include '../../login.php';

    $_GET['level'] = 5;
    include 'getUser.php';

    $name = @$_POST['name'];
    $id = @$_POST['id'];

    if(!isset($id)) {
        // Get userID
        $query = "SELECT id FROM ust_users WHERE name = :name";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
    }
    // Delete access
    $query = "DELETE FROM ust_access WHERE userid = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Delete user
    $query = "DELETE FROM ust_users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
?>
