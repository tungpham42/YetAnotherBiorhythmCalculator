<?php
    /**
     * Add or remove a domain access from an user
     * @HTTP POST
     * @param {String} type - add/remove
     * @param {String} domain
     * @param {Int} userid
     */
    include '../../login.php';
    $_GET['level'] = 4;
    include 'getUser.php';

    $type   = $_POST['type'];
    $domain = $_POST['domain'];
    $thatUserId = $_POST['userid'];

    switch($type){

        case 'add':
            $query = "INSERT INTO ust_access (userid, domain) VALUES (:thatUserId, :domain)";
        break;
        
        case 'remove':
            $query = "DELETE FROM ust_access WHERE userid = :thatUserId AND domain = :domain";
        break;
    }

    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->bindValue(':thatUserId', $thatUserId, PDO::PARAM_INT);
    $stmt->execute();
?>
