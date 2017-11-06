<?php
    include 'login.php';
     
    // Get POST Data
    $page             = @$_POST['page'];
    if($page == '') die('Invalid page url');

    $domainName       = @$_POST['domain'];    
    $resolution       = $_POST['resolution'];
    $what             = $_POST['what'];
   
    // Make sure domain is either set from POST or null
    $domainWHERE = $domainName != '' ?  "WHERE domain =:domain" : '';

    // For scrollmap simply return movement data
    $what = $what == 'scrollmap' ? 'movements' : $what;

    // Quick SQL injection protection
    if($what !== 'movements' && $what !== 'clicks') {
        die('Datatype invalid: '. $what);
    }

    if($resolution == -1) {
        $resolution = '';
    } else {
        $width = explode(" ",$resolution);
        $width = $width[0];

        // If a resolution is set, only search by width
        $resolution =  " AND resolution LIKE :width";
    }
      
    /**
     * Load data from database
     * @param {String} what - type of data to get (clicks/movements)
     */

    // Return multiple JSONs containing resolution and count
    $query = "SELECT * FROM ust_" .$what. "
                WHERE client IN (
                SELECT ID FROM ust_clientpage
                WHERE page = :page" .$resolution. " 
                        AND clientid IN (
                            SELECT id FROM ust_clients ".$domainWHERE."
                        )
                )";

    $stmt = $db->prepare($query);
    if($domainWHERE != '') $stmt->bindValue(':domain', $domainName, PDO::PARAM_STR);
    if($resolution != '') $stmt->bindValue(':width', $width.'%', PDO::PARAM_STR);
    $stmt->bindValue(':page', $page, PDO::PARAM_STR);
    $stmt->execute();

    $movements = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $movements[] = preg_replace("{\\\}","",$row['data']);
    }

    echo json_encode($movements);

?>
