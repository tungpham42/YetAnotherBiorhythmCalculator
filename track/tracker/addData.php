<?php
ignore_user_abort(true);

// Turn off gzip compression
if (function_exists( 'apache_setenv' )) apache_setenv( 'no-gzip', 1 );
ini_set('zlib.output_compression', 0);

// Turn on output buffering if necessary
if (ob_get_level() == 0) ob_start();

// Remove content encoding like gzip etc.
header('Content-encoding: none', true);

// Return 1x1 pixel transparent gif
header("Content-type: image/gif");

// Don't cache the pixel
header("Content-Length: 42");
header("Cache-Control: private, no-cache, no-cache=Set-Cookie, proxy-revalidate");
header("Expires: Wed, 11 Jan 2000 12:59:00 GMT");
header("Last-Modified: Wed, 11 Jan 2006 12:59:00 GMT");
header("Pragma: no-cache");

echo base64_decode('R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABA‌​AEAAAICRAEA');

// Flush all output buffers. No reason to make the user wait. 
ob_flush();
flush();
ob_end_flush();

/**
 * userTrack tracking logic starts here
 */
  include '../dbconfig.php';

  // Here's the argument from the client.
  $clientPageID = $_GET['i']; // clientPageID
  $json         = null;
  

  // Update time of latest known activity
  $query = "UPDATE `ust_clientpage` SET last_activity = NOW() WHERE id = :clientPageID";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
  $stmt->execute();

  // If $_GET['w'] is set we have a full recording
  if(isset($_GET['w'])) {
      $json = $_GET['r']; // record

      try {
          $query = "INSERT INTO `ust_records` (client, record) VALUES (:clientPageID, :json)";
          $stmt = $db->prepare($query);
          $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
          $stmt->bindValue(':json', $json, PDO::PARAM_STR);
          $stmt->execute();
      }
      catch (PDOException $e) {
          die("Could not insert full record into database.\n" . $query);
      }
      die();
  }

  // Add the partial
  if(isset($_GET['p'])) {
      $partial = $_GET['p'];
      try {
          $query = "INSERT INTO `ust_partials` (client, record) VALUES (:clientPageID, :json)
                    ON DUPLICATE KEY UPDATE record = CONCAT(record, :json2)";
          $stmt = $db->prepare($query);
          $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
          $stmt->bindValue(':json', $partial, PDO::PARAM_STR);
          $stmt->bindValue(':json2', $partial, PDO::PARAM_STR);
          $stmt->execute();
      }
      catch (PDOException $e) {
          echo "Could not insert partial record into database.\n" . $query;
      }
  }

  // Add the movements
  if(isset($_GET['m'])) {
      $movements = $_GET['m'];
      try {
          $query = "INSERT INTO `ust_movements` (client, data) VALUES (:clientPageID, :json)";
          $stmt = $db->prepare($query);
          $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
          $stmt->bindValue(':json', $movements, PDO::PARAM_STR);
          $stmt->execute();
      }
      catch (PDOException $e) {
          echo "Could not insert data into database.\n" . $query;
      }
  }

  // Add the clicks
  if(isset($_GET['c'])) {
      $clicks = $_GET['c'];
      try {
          $query = "INSERT INTO `ust_clicks` (client, data) VALUES (:clientPageID, :json)";
          $stmt = $db->prepare($query);
          $stmt->bindValue(':clientPageID', $clientPageID, PDO::PARAM_INT);
          $stmt->bindValue(':json', $clicks, PDO::PARAM_STR);
          $stmt->execute();
      }
      catch (PDOException $e) {
          echo "Could not insert data into database.\n" . $query;
      }
  }
?>
