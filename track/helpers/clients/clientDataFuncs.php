<?php
    include dirname(__FILE__).'/../../lib/php/array_column.php';


    function getVisitedPagesData($db, $clientID) {
        // Get a list of all the pages that he visited
        $query = "SELECT page, date, last_activity FROM ust_clientpage
                  WHERE clientid = :clientid";
        $stmt  = $db->prepare($query);
        $stmt->bindValue(':clientid', $clientID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getTags($db, $clientID) {
        // Get all tags for current user
        $query = "SELECT tag FROM ust_client_tag
                  WHERE clientid = :clientid";
        $stmt  = $db->prepare($query);
        $stmt->bindValue(':clientid', $clientID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * Used for dry coding to search in either `ust_records` or `ust_partials`.
     */
    function getRecordFromClientpageInTable($db, $clientpageID, $tableName) {
        $query = "SELECT id FROM `$tableName` 
                  WHERE client = :clientpageID";

        $stmt  = $db->prepare($query);
        $stmt->bindValue(':clientpageID', $clientpageID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Returns the first `clientpageID` visited by the given `clientID`
    function getFirstClientpageID($db, $clientID) {
        $query = "SELECT id FROM ust_clientpage
                WHERE clientid = :clientid
                ORDER BY id ASC LIMIT 1";

        $stmt  = $db->prepare($query);
        $stmt->bindValue(':clientid', $clientID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    // Returns the `recordid` associated with the given `clientpageID`
    function getRecordFromClientpage($db, $clientpageID) {
        // Get first record ID FROM ust_records
        $record = getRecordFromClientpageInTable($db, $clientpageID, 'ust_records');
        
        // If it was not found in records, search in partials
        if(!$record){
            $record = getRecordFromClientpageInTable($db, $clientpageID, 'ust_partials');
        }

        return $record;
    }


    function getTimeSpent($visitedPagesData) {
        // Get approximate time spent by the visitor
        $times = array_column($visitedPagesData, 'date');
        $last_times = array_column($visitedPagesData, 'last_activity');
        $timeSpent = 0;
        if(count($times) >= 1) {
            $timeSpent = strtotime(end($last_times)) - strtotime($times[0]);
            
            // Maybe last second was not recorded
            $timeSpent += 1;
        }

        return $timeSpent;
    }

    function getInfoFromToken($token, $deviceType) {

        // Get IP from token
        $ip = preg_split("/#/", $token);
        $ip = $ip[0];

        // Get browser from token if not mobile
        if($deviceType == 1) {
            $br = 'mobile';
        } else {
            $br = preg_split('/@/',$token);
            $br = $br[1];
        }

        return array(
            'ip' => $ip,
            'browser' => $br
        );
    }
?>