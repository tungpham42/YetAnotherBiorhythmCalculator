<?php
// Returns an object containing the current user's
// -> level
include '../../login.php';

echo json_encode(
	array(
	'level' => $row['level']
	)
);
?>
