<?php
/**
 * Update a user's username, password or level
 * @HTTP POST //actually update
 * @param {String} dataType
 * @param {String} value
 * @param {Int} userId
 */
include '../../login.php';
$_GET['level'] = 5;
$included =  true;
include 'getUser.php';

// Get data from POST
$dataType = strtolower($_POST['dataType']);
$value = $_POST['value'];
$thatUserId = strtolower($_POST['userId']);

// SQL injection protection
$allowedColumns = array('name', 'password', 'level');
if(!in_array($dataType, $allowedColumns)) {
    die('Datatype invalid: '. $dataType);
}

// User can not modify it's own permissions
if($userId == $thatUserId && $dataType != 'password')
    die('You can not change your own name or level!');
    
// Encrypt password
if($dataType == 'password')
    $value = md5($value);
   
$query = "UPDATE ust_users SET " .$dataType. " = :value WHERE id = :thatUserId";
$stmt = $db->prepare($query);
$stmt->bindValue(':value', $value, PDO::PARAM_STR);
$stmt->bindValue(':thatUserId', $thatUserId, PDO::PARAM_INT);
$stmt->execute();

?>
