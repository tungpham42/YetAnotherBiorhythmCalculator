<?php
    /**
     * Returns all userTrack users and the domains
     * they have access to
     */
    include '../../login.php';
    $_GET['level'] = 4;
    $included = 1;
    include 'getUser.php';

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
