<?php

    include '../dbconfig.php';

    // Cross-domain request
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type');

    $userResolution = $_POST['r'];
    $token          = $_POST['t'];
    $path           = urldecode($_POST['u']);
    $domain         = $_POST['d'];
    $clientID       = @$_POST['c'];
    $referrer       = @$_POST['s'];
    $deviceType     = $_POST['v'];

    // Validate input
    if($path == '' || $path == null || $path == 'null')
        die('Invalid page url');

    // Referrer is valid URL
    if(isset($referrer) && $referrer != '' 
       && !filter_var($referrer, FILTER_VALIDATE_URL))
        die('Invalid referrer url' . $referrer);

    // Make sure given clientID is valid if it exists
    if($clientID && $clientID != 'undefined') {
        // See if client already exists based on the toekn
        $query  = "SELECT id FROM `ust_clients` WHERE id = :clientID";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':clientID', $clientID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // If not found, unvalidate the ID
        if(!$row) {
          $clientID = 'undefined';
        }
    }

    // If we don't already have the clientID find or create it
    if(!$clientID || $clientID == 'undefined') {
        
        /* ******************************
         * Get browser info
         * ******************************/
        $browser = array(
           'version'   => '0.0.0',
           'majorver'  => 0,
           'minorver'  => 0,
           'build'     => 0,
           'name'      => 'unknown',
           'useragent' => ''
         );

        $browsers = array(
          'edge', 'trident', 'opr', 'chrome', 'firefox', 'msie', 'safari', 'mozilla', 'seamonkey', 'konqueror', 'netscape', 
          'gecko', 'navigator', 'mosaic', 'lynx', 'amaya', 'omniweb', 'avant', 'camino', 'flock', 'aol'
        );

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $browser['useragent'] = $_SERVER['HTTP_USER_AGENT'];
            $user_agent = strtolower($browser['useragent']);
            foreach($browsers as $_browser) {
                if (preg_match("/($_browser)[\/ ]?([0-9.]*)/", $user_agent, $match)) {
                    $browser['name'] = $match[1];
                    $browser['version'] = $match[2];
                    @list($browser['majorver'], $browser['minorver'], $browser['build']) = explode('.', $browser['version']);
                    break;
                }
            }
        }

        if($browser['name'] == 'edge') $browser['name'] = 'msie';
        if($browser['name'] == 'trident') {
          if(preg_match("/rv:([0-9.]*)/", $user_agent, $match)) {
            $browser['name'] = 'msie';
            $browser['majorver'] = $match[1] | 0;
          }

        }
        if($browser['name'] == 'opr') $browser['name'] = 'opera';

        $browser = $browser['name'].' '.$browser['majorver'];


        /* ******************************
         * Create the client
         * ******************************/

        // Compute unique client token : IP#RANDID@BROWSER
        $remote_addr   = $_SERVER['REMOTE_ADDR'];
        
        // Check for HTTP_X_FORWARDED_FOR HEADER
        // THANKS TO: victorbaptista for this fix :)
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $tmpip = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $remote_addr = trim($tmpip[0]);
        }

        // Fix for IPv6 ::1 (localhost)
        if($remote_addr == '::1') $remote_addr = '127.0.0.1';

        $token   = $remote_addr.'#'.$token.'@'.$browser;

        // See if client already exists based on the toekn
        $query  = "SELECT id FROM `ust_clients` 
                   WHERE token = :token 
                     AND domain = :domain";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();

        $clientID = $result['id'];

        if($clientID == false){
            if(!isset($referrer)) $referrer = '';

            // Create a new client
            $query = "INSERT INTO `ust_clients` (DOMAIN, TOKEN, REFERRER, DEVICE_TYPE)
                             VALUES (:domain, :token, :referrer, :deviceType)";

             $stmt = $db->prepare($query);
               $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
               $stmt->bindValue(':token', $token, PDO::PARAM_STR);
               $stmt->bindValue(':referrer', $referrer, PDO::PARAM_STR);
               $stmt->bindValue(':deviceType', $deviceType, PDO::PARAM_INT);
               $stmt->execute();

            // Get the ID of the newly inserted value
            $clientID = $db->lastInsertId();
        }

    }
    
    // Now that we have the client, we need to add a new clientPage and get its ID
    $query = "INSERT INTO `ust_clientpage` (DATE, LAST_ACTIVITY, PAGE, RESOLUTION, CLIENTID)
                    VALUES (NOW(), NOW(), :page, :resolution, :clientid)";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':page', $path, PDO::PARAM_STR); 
    $stmt->bindValue(':resolution', $userResolution, PDO::PARAM_STR); 
    $stmt->bindValue(':clientid', $clientID, PDO::PARAM_INT);
    $stmt->execute();

    $clientPageID = $db->lastInsertId();

    $res = array(
        'clientID' => $clientID,
        'clientPageID' => $clientPageID,
    );

    echo json_encode($res);
?>