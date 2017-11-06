<?php
    // include '../../login.php';
    include dirname(__FILE__).'/../../dbconfig.php';
    include dirname(__FILE__).'/../clients/clientDataFuncs.php';

    $domain  = $_REQUEST['domain'];

    // MySQL 5.7 Group by FIX
    $db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    
    $query = "SELECT * FROM ust_clients 
              INNER JOIN ust_clientpage
              ON ust_clients.id = ust_clientpage.clientid
              WHERE domain = :domain 
              AND date >= DATE_SUB(NOW(),INTERVAL 10 MINUTE)
              GROUP by token ORDER BY ust_clientpage.date DESC";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $res = array();
    foreach($data as $row) {
        $clientID = $row['clientid'];
        $visitedPagesData = getVisitedPagesData($db, $clientID);

        // Get a space separated list of pages visited
        $visitedPages = array_column($visitedPagesData, 'page');
        $visitedPagesList = implode(' ', $visitedPages);

        $clientpageID = getFirstClientpageID($db, $clientID);
        $firstRecord = getRecordFromClientpage($db, $clientpageID);
        $firstRecordID = count($firstRecord) == 1 ? $firstRecord['id'] : -1;

        $clientInfo = getInfoFromToken($row['token'], $row['device_type']);

        // Get approximate time spent by the visitor
        $timeSpent = getTimeSpent($visitedPagesData);

        // Get tags
        $tags = getTags($db, $row['id']);

        $res[] = array(
                'ip' => $clientInfo['ip'],
                'browser' => $clientInfo['browser'],
                'referrer' => $row['referrer'],
                'pageHistory' => $visitedPagesList,
                'tags' => $tags,
                'recordid' => $firstRecordID,
                'timeSpent' => $timeSpent,
                'id' => $row['id'],
                'clientid' => $clientID,
                'token'=> $row['token'],
        );
    }

    echo json_encode($res);
?>
