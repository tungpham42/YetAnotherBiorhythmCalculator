<?php

//Unset cookies
setcookie("userTrackPassword", '', time()-3600, "/");
setcookie("userTrackUsername", '', time()-3600, "/");
setcookie("userTrackUserid", '', time()-3600, "/");
setcookie("userTrackUserLevel", '', time()-3600, "/");

//Redirect
sleep(1);
header("location:../../login.php");
?>