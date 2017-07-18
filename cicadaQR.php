<?php
$color = (isset($_POST['color'])) ? $_POST['color']: '';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cicada QR</title>
	</head>
	<body>
		<form action="/cicadaQR.php" method="POST">
			<label for="color">Color:</label>
			<input type="text" name="color" value="<?php echo $color; ?>" />
			<input type="submit" value="Enter" />
		</form>
<?php
if (isset($_POST['color'])):
	$image = new Imagick('./images/cicada.jpg');
	$inputColor = new ImagickPixel();
	$inputColor->setColor((string)$color);
	$outputColor = new ImagickPixel();
	$outputColor->setColor('red');
	$image->paintOpaqueImage($inputColor, $outputColor, 10);
	$image->writeImage('./images/cicadaResult.jpg');
	echo '<img src="./images/cicadaResult.jpg" />';
endif;
?>
	</body>
</html>