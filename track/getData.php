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

    // SQL injection protection
    $allowedColumns = array('movements', 'clicks', 'record');
    if(!in_array($what, $allowedColumns)) {
        die('Datatype invalid: '. $what);
    }
 
    if($what == 'record') {
        $recordid = $_POST['recordid'];
    }

    if($resolution == -1) {
        $resolution = '';
    }
    else {
        $width = explode(" ",$resolution);
        $width = $width[0];

        // If a resolution is set, only search by width
        $resolution =  " AND resolution LIKE :width";
    }
      
    /**
     * Load data from database
     * @param {String} what - type of data to get (record/clicks/movements)
     */
    switch($what){
        // Return multiple JSONs containing resolution and count
        case 'movements':
        case 'clicks':
     
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
        break;

        case 'record':
            $query = "SELECT * FROM ust_records 
                      WHERE id = :recordid";
            
            $stmt = $db->prepare($query);
            $stmt->bindValue(":recordid", $recordid, PDO::PARAM_INT);
            $stmt->execute();
            
            // Maybe it is a partial recording
            if($stmt->rowCount() != 1){
                $query = "SELECT * FROM ust_partials 
                          WHERE id = :recordid";
                
                $stmt = $db->prepare($query);
                $stmt->bindValue(":recordid", $recordid, PDO::PARAM_INT);
                $stmt->execute();
                
                if($stmt->rowCount() != 1)
                    die("Could not find record width id $recordid in database!");
            }
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Remove "\" from the beggining, if for some reason exists
            $json = preg_replace("{\\\}","",$row['record']);
            
            echo $json;
        break;
        
        default:
            echo 'Not supported statistics type '.$what;
        break;  
    }
?>
