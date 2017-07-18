<?php
    include '../dbconfig.php';
    
    $domain = @$_POST['domain'];
    
    // Make sure domain is either set from POST or null
    if($domain != '')
        $domainWHERE = " WHERE domain = :domain";
    else
        $domainWHERE = "";
    
    // Get all distinct pages FROM ust_clients in $domain
    $query = "SELECT DISTINCT page FROM `ust_clientpage`
              WHERE clientid IN (
                  SELECT id FROM ust_clients" 
                 .$domainWHERE."
              )";

    $stmt = $db->prepare($query);
    if($domain != '') $stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
    $stmt->execute();
    
    $res = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $res[] = $row['page'];
    }
    
    echo json_encode($res);
?>