<?php
    include '../login.php';
    include '../lib/php/array_column.php';
    include 'clients/clientDataFuncs.php';

    // Limit clients before displaying them
    $included = 1;
    include 'limitRecordNumber.php';
    
    $from        = @$_POST['from'];
    $take        = @$_POST['take'];
    $domain      = $_POST['domain'];
    $order       = @$_POST['order'];
    $startDate   = @$_POST['startDate'];
    $endDate     = @$_POST['endDate'];
    $tagFilters  = @$_POST['tagFilters'];
    $liveOnly    = @$_POST['liveOnly'];

    // Date interval condition can be null or given by POST
    if($liveOnly !== 'true') {
        $dateWhere = $startDate != '' ? "AND CAST(`date` AS DATE) BETWEEN :startDate AND :endDate ": '';
    } else {
        $dateWhere = "AND last_activity > (NOW() - INTERVAL 1 MINUTE) ";
    }

    // Tag filtering
    $tagJoin = '';
    if(count($tagFilters) > 0) {
        $tag = $tagFilters[0];
        $count = 1;

        foreach($tagFilters as $tag) {
            $tagJoin .= " JOIN ust_client_tag tag$count ON ust_clients.id = tag$count.clientid AND tag$count.tag = :tag$count";
            $count++;
        }
    }

    // Ordering
    $order  = $order  == '' ? "DESC" : $order;
    
    // Pagination
    if($from == '') {
        $from = 0;
        $take = 6;
    }
    
    /**
     * Get all clients that match the current filters
     */
    
    // MySQL 5.7 Group by FIX
    $db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    
    $query = "SELECT * FROM ust_clients 
              INNER JOIN ust_clientpage
              ON ust_clients.id = ust_clientpage.clientid"
              .$tagJoin
              ." WHERE domain = :domain "
              .$dateWhere
              ." GROUP by token ORDER BY ust_clientpage.date $order";

    $clientsStmt = $db->prepare($query);

    // Bind domain
    $clientsStmt->bindValue(':domain', $domain, PDO::PARAM_STR);

    // Bind date
    if($liveOnly !== 'true' && $dateWhere != '') {
        $clientsStmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
        $clientsStmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    }

    // Bind tags
    if(count($tagFilters) > 0) {
        $count = 1;
        foreach($tagFilters as $tag) {
            $clientsStmt->bindValue(":tag$count", $tag, PDO::PARAM_STR);
            $count++;
        }
    }

    $clientsStmt->execute();
    
    $cnt = $clientsStmt->rowCount();
    $data = $clientsStmt->fetchAll(PDO::FETCH_ASSOC);

    // PAGINATION: Limit the data to the current displayed page
    $data = array_slice($data, $from, $take);
       
    // Array to store the data to be returned
    $res = array();
    
    // Count the number of pages visited
    foreach($data as $row){
    
        $clientpageID = getFirstClientpageID($db, $row['clientid']);
        
        // If no first record was found, set recordid to -1
        $firstRecord = getRecordFromClientpage($db, $clientpageID);
        $recordid = count($firstRecord) == 1 ? $firstRecord['id'] : -1;

        $visitedPagesData = getVisitedPagesData($db, $row['clientid']);
        $visitedPages = array_column($visitedPagesData, 'page');
        $visitedPagesList = implode(' ', $visitedPages);

        $timeSpent = getTimeSpent($visitedPagesData);

        // Count number of total pages visited
        $nr = count($visitedPages);
        
        $clientInfo = getInfoFromToken($row['token'], $row['device_type']);

        // Get tags
        $tags = getTags($db, $row['clientid']);

        // Check if the recording was watched or not
        $query = "SELECT COUNT(*) FROM ust_user_client_watched
                  WHERE clientid = :clientid AND userid = :userid";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':clientid', $row['clientid'], PDO::PARAM_INT);
        $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $watched = $stmt->fetchColumn();

        // Build the object with all info for current client
        $res[] = array(
                        'date' => $row['date'],
                        'ip' => $clientInfo['ip'],
                        'resolution' => $row['resolution'],
                        'browser' => $clientInfo['browser'],
                        'referrer' => $row['referrer'],
                        'pageHistory' => $visitedPagesList,
                        'tags' => $tags,
                        'recordid' => $recordid,
                        'timeSpent' => $timeSpent,
                        'id' => $row['id'],
                        'clientpageid' => $clientpageID,
                        'clientid' => $row['clientid'],
                        'nr' => $nr,
                        'token'=> $row['token'],
                        'watched' => $watched
                );
    };

  echo json_encode(array('clients' => $res, 'count' => $cnt));
?>
