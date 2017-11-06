<?php
    require_once '../login.php';
    require_once '../permissions.php';
  
    try { checkPermission('CHANGE_SETTINGS'); } catch(Exception $e) { die($e->getMessage());}

    $delay           = $_POST['delay'];
    $recordClicks    = $_POST['recordClick'];
    $recordMoves     = $_POST['recordMove'];
    $recordKey       = $_POST['recordKey'];
    $maxMoves        = $_POST['maxMove'];
    $static          = $_POST['static'];
    $serverPath      = $_POST['serverPath'];
    $ignoreGET       = $_POST['ignoreGET'];
    $ignoreIPs       = $_POST['ignoreIPs'];
    $percentangeRecorded = $_POST['percentangeRecorded'];

    /*** Directly edit the tracker.js file ***/
    $filename = '../tracker.js';
    $contents = file_get_contents($filename);
    
    $pattern = '/recordClick: \S+,/';
    $newpat = 'recordClick: '.$recordClicks.',';
    $contents = preg_replace($pattern, $newpat, $contents);
    
    $pattern = '/delay: \d+,/';
    $newpat = 'delay: '.$delay.',';
    $contents = preg_replace($pattern, $newpat, $contents);
    
    $pattern = '/isStatic: \S+,/';
    $newpat = 'isStatic: '.$static.',';
    $contents = preg_replace($pattern, $newpat, $contents);
    
    $pattern = '/maxMoves: \d+,/';
    $newpat = 'maxMoves: '.$maxMoves .',';
    $contents = preg_replace($pattern, $newpat, $contents);
    
    $pattern = '/recordMove: \S+,/';
    $newpat = 'recordMove: '.$recordMoves .',';
    $contents = preg_replace($pattern, $newpat, $contents);
    
    $pattern = '/recordKeyboard: \S+,/';
    $newpat = 'recordKeyboard: '.$recordKey .',';
    $contents = preg_replace($pattern, $newpat, $contents);
    
    $pattern = '/serverPath: \S+,/';
    $newpat = 'serverPath: "'.$serverPath .'",';
    $contents = preg_replace($pattern, $newpat, $contents);

    // Wrap each GET param in quotes
    $ignoreGET = explode(',', $ignoreGET);
    function wrap_in_quotes($el)  { 
      return "'{$el}'"; 
    }
    $ignoreGET = array_map('wrap_in_quotes', $ignoreGET);
    $ignoreGET = implode(',', $ignoreGET);
    $pattern = '/ignoreGET: \[.+],/';
    $newpat = 'ignoreGET: ['.$ignoreGET .'],';
    $contents = preg_replace($pattern, $newpat, $contents);

    // Wrap each IP in quotes
    $ignoreIPs = explode(',', $ignoreIPs);
    $ignoreIPs = array_map('wrap_in_quotes', $ignoreIPs);
    $ignoreIPs = implode(',', $ignoreIPs);
    $pattern = '/ignoreIPs: \[.+],/';
    $newpat = 'ignoreIPs: ['.$ignoreIPs .'],';
    $contents = preg_replace($pattern, $newpat, $contents);

    $pattern = '/percentangeRecorded: \d+,/';
    $newpat = 'percentangeRecorded: '.$percentangeRecorded .',';
    $contents = preg_replace($pattern, $newpat, $contents);
    
    file_put_contents($filename, $contents);
?>