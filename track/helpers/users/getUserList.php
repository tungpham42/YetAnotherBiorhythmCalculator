<?php
    /**
     * Returns all userTrack users and the domains
     * they have access to
     */
    require_once '../../login.php';
    require_once '../../permissions.php';
    
    try { checkPermission('GET_USERS_LIST'); } catch(Exception $e) { die($e->getMessage());}

    $query = "SELECT id, name, level FROM ust_users";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $count = -1;
    $res = array();
    while($res[++$count] = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        
        // Get domains
        $domains = array();
        $query = "SELECT domain FROM ust_access WHERE userid = :userid";
        $stmt2 = $db->prepare($query);
        $stmt2->bindValue(':userid', $res[$count]['id'], PDO::PARAM_INT);
        $stmt2->execute();

        while($domain = $stmt2->fetch(PDO::FETCH_ASSOC)){    
            $domains[] = $domain['domain'];
        }
        
        $res[$count]['domains'] = $domains;
    };

    echo json_encode($res);
?>
