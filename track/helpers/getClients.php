<?php
    include '../login.php';
    include '../lib/php/array_column.php';

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

    // Date interval condition can be null or given by POST
    $dateWhere = $startDate != '' ? "AND CAST(`date` AS DATE) BETWEEN :startDate AND :endDate": '';

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
    // Count the total number of clients
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
    if($dateWhere != '') {
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

    // Limit the data to the current page
    $data = array_slice($data, $from, $take);
       
    // Array to store the data to be returned
    $res = array();
    
    // Count the number of pages visited
    foreach($data as $row){
    
        // Get first record ID FROM ust_records
        $query = "SELECT id FROM ust_records 
                  WHERE client IN (
                    SELECT id FROM ust_clientpage
                    WHERE clientid = :clientid
                  ) ORDER BY id LIMIT 1";

        $stmt  = $db->prepare($query);
        $stmt->bindValue(':clientid', $row['clientid'], PDO::PARAM_INT);
        $stmt->execute();

        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If it was not found in records, search in partials
        if(!$record){
            $query = "SELECT id FROM ust_partials
                      WHERE client IN (
                        SELECT id FROM ust_clientpage
                        WHERE clientid = :clientid
                      ) ORDER BY id LIMIT 1";

            $stmt  = $db->prepare($query);
            $stmt->bindValue(':clientid', $row['clientid'], PDO::PARAM_INT);
            $stmt->execute();

            $record = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // If no first record was found, set recordid to -1
        if(count($record) == 1)
            $recordid = $record['id'];
        else
            $recordid = -1;
        
        // Get a list of all the pages that he visited
        $query = "SELECT page, date, last_activity FROM ust_clientpage
                  WHERE clientid = :clientid";
        $stmt  = $db->prepare($query);
        $stmt->bindValue(':clientid', $row['clientid'], PDO::PARAM_INT);
        $stmt->execute();

        $visitedPagesData = $stmt->fetchAll();

        $visitedPages = array_column($visitedPagesData, 'page');
        $visitedPagesList = implode(' ', $visitedPages);
 
        // Get approximate time spent by the visitor
        $times = array_column($visitedPagesData, 'date');
        $last_times = array_column($visitedPagesData, 'last_activity');
        $timeSpent = 0;
        if(count($times) >= 1) {
            $timeSpent = strtotime(end($last_times)) - strtotime($times[0]);
            
            // Maybe last second was not recorded
            $timeSpent += 1;
        }

        // Count number of total pages visited
        $nr = count($visitedPages);
        
        // Get IP from token
        $ip = preg_split("/#/", $row['token']);
        $ip = $ip[0];

        // Get browser from token
        $br = preg_split('/@/',$row['token']);
        $br = $br[1];
        
        // Get all tags for current user
        $query = "SELECT tag FROM ust_client_tag
                  WHERE clientid = :clientid";
        $stmt  = $db->prepare($query);
        $stmt->bindValue(':clientid', $row['clientid'], PDO::PARAM_INT);
        $stmt->execute();

        $tags = $stmt->fetchAll();
        $tags = array_column($tags, 'tag');

        // Build the object with all info for current client
        $res[] = array(
                        'date' => $row['date'],
                        'ip' => $ip,
                        'resolution' => $row['resolution'],
                        'browser' => $br,
                        'pageHistory' => $visitedPagesList,
                        'tags' => $tags,
                        'recordid' => $recordid,
                        'timeSpent' => $timeSpent,
                        'id' => $row['id'],
                        'clientid' => $row['clientid'],
                        'nr' => $nr,
                        'token'=> $row['token'],
                );
    };

  echo json_encode(array('clients' => $res, 'count' => $cnt));
?>
