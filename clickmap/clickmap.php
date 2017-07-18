<?php 
$pdo = new PDO('sqlite:'.realpath($_SERVER['DOCUMENT_ROOT']).'/db/clickmap');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
if (isset($_POST['l'])) {
	$result = $pdo->prepare('INSERT INTO clickmap SET x=:x,y=:y,location=:l');
	$result->execute(array(':x' => $_POST['x'], ':y' => $_POST['y'], ':l' => $_POST['l']));
};
if (isset($_GET['l'])) {
	$result = $pdo->prepare('SELECT x, y FROM clickmap WHERE location=:l ORDER BY id DESC limit 200');
	$result->execute(array(':l' => $_GET['l']));
	$html = '<div id="clickmap-container">';
	if ($result) {
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$html .= sprintf('<div style="left:%spx;top:%spx"></div>', $row['x']-10, $row['y']-10);
		}
	}
	$html .= '</div>';
	echo $html;
};
?>