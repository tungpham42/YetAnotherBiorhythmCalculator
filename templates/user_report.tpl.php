<?php
$user_report_key = "";
if ($_SERVER['SERVER_NAME'] == $first_domain) {
    $user_report_key = 'e5e2dabc-3ca8-4e81-9a08-42eea8894b60';
} else if ($_SERVER['SERVER_NAME'] == $second_domain) {
    $user_report_key = '8ad8350d-e32a-4c15-94b9-14e6b7212def';
}
?>
<script type="text/javascript">
window._urq = window._urq || [];
_urq.push(['initSite', <?php echo $user_report_key; ?>]);
(function() {
var ur = document.createElement('script'); ur.type = 'text/javascript'; ur.async = true;
ur.src = ('https:' == document.location.protocol ? 'https://cdn.userreport.com/userreport.js' : 'http://cdn.userreport.com/userreport.js');
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ur, s);
})();
</script>