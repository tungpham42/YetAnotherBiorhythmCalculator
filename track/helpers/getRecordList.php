<?php
  /**
   * Returns a list with all pages visisted by the current client
   * @param {String} token - The token of the client to get the records for
   * @returns {JSON} - A list containing page name, time and id
   */

  include '../dbconfig.php';
  
  $token = $_POST['token'];
    
  // Get clientID based on token
  $query = "SELECT id FROM ust_clients
            WHERE token = :token";
  $stmt = $db->prepare($query);
  $stmt->bindValue(":token", $token, PDO::PARAM_STR);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $clientID = $row['id'];

  // Get all clientPages based on the clientID
  $query = "SELECT * FROM ust_clientpage
            WHERE clientid = :clientID";
  $stmt = $db->prepare($query);
  $stmt->bindValue(":clientID", $clientID, PDO::PARAM_INT);
  $stmt->execute();

  $res = array();

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $clientPageID = $row['id'];
    $date = $row['date'];
    $page = $row['page'];
    $resolution = $row['resolution'];

    // Get record ID based on clientID
    $query = "SELECT id FROM ust_records
              WHERE client = :clientPageID
              UNION
              SELECT id FROM ust_partials
              WHERE client = :clientPageID2";
    $idStmt = $db->prepare($query);
    $idStmt->bindValue(":clientPageID", $clientPageID, PDO::PARAM_INT);
    $idStmt->bindValue(":clientPageID2", $clientPageID, PDO::PARAM_INT);
    $idStmt->execute();

    $idRow = $idStmt->fetch(PDO::FETCH_ASSOC);
    $id = $idRow['id'];

    $res[] =  array('id' => $id ? $id : 0,
                    'page' => $page,
                    'res' => $resolution,
                    'date' => $date
              );
  }

    echo json_encode($res);
?>
