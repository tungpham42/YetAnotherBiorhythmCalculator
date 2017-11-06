<?php
    /**
     * Returns the next record for current session playback.
     * @param {String} id - The id of the last clientPageID played. [FROM ust_clientpage table]
     * @returns {JSON} - Info of next record (id, page, resolution)
     */

    include '../dbconfig.php';
    
    $clientPageID = @$_POST['id'];
      
    // Get clientID based on clientPageID
    $query = "SELECT clientid as clientID FROM ust_clientpage
              WHERE id = :lastClientPageID";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":lastClientPageID", $clientPageID, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $clientID = $row['clientID'];

    $nextRecordID = 0;
    $nextClientPageID = 0;
    getNextClientpageID();

    function getNextClientpageID() {
        global $db, $clientID, $clientPageID, $nextRecordID, $nextClientPageID, $lastRecordID;

        // Get next clientPageID and info
        $query = "SELECT * FROM ust_clientpage
                  WHERE id > :clientPageID AND clientid = :clientID
                  LIMIT 1";

        $stmt = $db->prepare($query);
        $stmt->bindValue(":clientID", $clientID, PDO::PARAM_INT);
        $stmt->bindValue(":clientPageID", $clientPageID, PDO::PARAM_INT);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {
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
            if ($nextRecordID == null) {
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
        if ($nextRecordID == $lastRecordID) {
            $nextRecordID = 0;
        }

            
        // If we don't have the next record but there is the next page, try to get the record after that
        if ($nextRecordID == false && $nextClientPageID !== 0) {
            $clientPageID = $nextClientPageID;
            $nextClientPageID = 0;
            getNextClientpageID();
        } else {
            echo json_encode(
                array('id' => $nextRecordID,
                      'clientpageid' => $nextClientPageID,
                      'page' => $nextPage,
                      'res' => $nextResolution)
            );
        }
    }
?>
