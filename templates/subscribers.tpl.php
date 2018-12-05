<?php
$subscribers_id = "";
if ($_SERVER['SERVER_NAME'] == $first_domain) {
    $subscribers_id = '5df8ac93-880e-4649-9ecf-70d07d55632d';
} else if ($_SERVER['SERVER_NAME'] == $second_domain) {
    $subscribers_id = '21378257-03a5-4c0e-bb7f-6b37c9085f47';
}
?>
<script type="text/javascript">
  var subscribersSiteId = '<?php echo $subscribers_id; ?>';
</script>
<script type="text/javascript" src="https://cdn.subscribers.com/assets/subscribers.js"></script>