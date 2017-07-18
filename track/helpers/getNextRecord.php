<?php
    /**
     * Returns the next record for current session playback.
     * @param {String} id - The id of the last record played. [FROM ust_records table]
     * @returns {JSON} - Info of next record (id, page, resolution)
     */

    include '../dbconfig.php';
    
    $lastRecordID = @$_POST['id'];
      
    // Get clientID based on recordID
    $query = "SELECT ust_clientpage.clientid as clientID, ust_records.client as clientPageID FROM ust_records
              INNER JOIN ust_clientpage ON ust_records.client = ust_clientpage.id
              WHERE ust_records.id = :lastid";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":lastid", $lastRecordID, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $clientID = $row['clientID'];
    $clientPageID = $row['clientPageID'];

    $nextRecordID = 0;
    $nextClientPageID = 0;

    // Get next clientPageID and info
    $query = "SELECT * FROM ust_clientpage
              WHERE id > :clientPageID AND clientid = :clientID
              LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":clientID", $clientID, PDO::PARAM_INT);
    $stmt->bindValue(":clientPageID", $clientPageID, PDO::PARAM_INT);
    $stmt->execute();

    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $res ) {

        $nextClientPageID = $res['id'];
        $nextResolution   = $res['resolution'];
        $nextPage         = $res['page'];
    
        // Get the record associated with nextClientPageID
        $query = "SELECT id FROM ust_records
                  WHERE client = :nextClientPageID
                  LIMIT 1";

        $stmt = $db->prepare($query);
        $stmt->bindValue(":nextClientPageID", $nextClientPageID, PDO::PARAM_INT);
        $stmt->execute();

        $nextRecordID = $stmt->fetchColumn();

        // If we don't have a next record try for a partial record
        if($nextRecordID == null) {
            $query = "SELECT id FROM ust_partials
                      WHERE client = :nextClientPageID
                      LIMIT 1";

            $stmt = $db->prepare($query);
            $stmt->bindValue(":nextClientPageID", $nextClientPageID, PDO::PARAM_INT);
            $stmt->execute();

            $nextRecordID = $stmt->fetchColumn();
        }
    }

    if (!$nextClientPageID) {
        $nextResolution = "0 0";
        $nextPage = '/';
    }

    // Make sure we do not loop the partial recording !@BUG: $last = $id but record did not play
    if ($nextRecordID == $lastRecordID) 
        $nextRecordID = 0;
    
    echo json_encode( array(
                           'id' => $nextRecordID, 
                           'page' => $nextPage,
                           'res' => $nextResolution
                          )
                  );
?>
