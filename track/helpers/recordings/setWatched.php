<?php
  /**
   * Sets the given clientID as being watched by the current user.
   * @global $userId (from login.php)
   */
  require_once '../../login.php';

  $clientID = $_POST['clientID'];

  $query = "INSERT IGNORE INTO `ust_user_client_watched` (clientid, userid) VALUES(:clientID, :userID)";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':clientID', $clientID, PDO::PARAM_INT);
  $stmt->bindValue(':userID', $userId, PDO::PARAM_INT);
  $stmt->execute();
?>