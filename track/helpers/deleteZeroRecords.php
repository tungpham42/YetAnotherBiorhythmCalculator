<?php
    include '../login.php';
    
    $domain = $_POST['domain'];
    
    // Get all the clients for the current page, ordered by date
    $query = "SELECT * FROM ust_clients
              INNER JOIN ust_clientpage
              ON ust_clients.id = ust_clientpage.clientid
              GROUP by token";
    $clientsStmt = $db->prepare($query);
    $clientsStmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $clientsStmt->execute();
       
    // Count the number of pages visited
    while($row = $clientsStmt->fetch(PDO::FETCH_ASSOC)){
    
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

        // No records found, delete client
        if($recordid == -1 || $recordid == null) {
                $clientID = $row['clientid'];

                $query = array();

                // Delete records
                $query[] = "DELETE FROM ust_records WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
                // Delete partial records
                $query[] = "DELETE FROM ust_partials WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
                // Delete movements
                $query[] = "DELETE FROM ust_movements WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
                // Delete clicks
                $query[] = "DELETE FROM ust_clicks WHERE client in (SELECT id FROM ust_clientpage WHERE clientid = :clientid)";
                // Delete clientpages
                $query[] = "DELETE FROM ust_clientpage WHERE clientid = :clientid";
                // Delete the client itself
                $query[] = "DELETE FROM ust_clients WHERE id = :clientid";

                foreach($query as $q){
                    $stmt = $db->prepare($q);
                    $stmt->bindValue(':clientid', $clientID, PDO::PARAM_INT);
                    $stmt->execute();
                }   
        }
    };
?>
