<script>
manipulateCommon();
<?php
if (!isset($_GET['p']) && $embed == 0 || in_array($p, $navs)):
?>
manipulateHome();
$('#dob').datepicker({
	dateFormat: 'yy-mm-dd',
	changeYear: true,
	changeMonth: true,
	yearRange: '0:<?php echo date('Y') ?>',
	defaultDate: '<?php echo ($dob != '') ? date('Y-m-d',strtotime($dob)): '1961-09-26'; ?>',
	maxDate: '<?php echo date('Y-m-d'); ?>',
	showButtonPanel: true,
	showAnim: ''
});
<?php
endif;
?>
manipulateEnd();
</script>
<?php
include template('adblock');
?>