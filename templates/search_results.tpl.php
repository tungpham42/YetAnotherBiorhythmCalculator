<?php
$cx = "";
if ($_SERVER['SERVER_NAME'] == $first_domain) {
  $cx = 'partner-pub-3585118770961536:2840253393';
} else if ($_SERVER['SERVER_NAME'] == $second_domain) {
  $cx = 'partner-pub-3585118770961536:1884186953';
}
?>
<script>
(function() {
	var cx = '<?php echo $cx; ?>';
	var gcse = document.createElement('script');
	gcse.type = 'text/javascript';
	gcse.async = true;
	gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//www.google.com/cse/cse.js?cx=' + cx;
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(gcse, s);
	manipulateLang();
})();
</script>
<gcse:searchresults-only></gcse:searchresults-only>