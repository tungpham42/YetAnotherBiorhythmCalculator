<?php
    /**
     * Add a new userTrack user from the admin dashboard
     */
    require_once '../../login.php';
    require_once '../../permissions.php';
    
    try { checkPermission('ADD_USER'); } catch(Exception $e) { die($e->getMessage());}

    $name = $_POST['name'];
    $pass = md5($_POST['pass']);

    $query = "INSERT INTO ust_users(name, password, level) VALUES(:name, :pass, '0')";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
    $stmt->execute();
?>
