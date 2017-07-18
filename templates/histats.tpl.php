<!-- Histats.com  (div with counter) -->
<center><div id="histats_counter"></div></center>
<!-- Histats.com  START  (aync)-->
<script type="text/javascript">var _Hasync= _Hasync|| [];
<?php if ($_SERVER['SERVER_NAME'] == $first_domain): ?>
_Hasync.push(['Histats.start', '1,3755000,4,4007,112,61,00011111']);
<?php elseif ($_SERVER['SERVER_NAME'] == $second_domain): ?>
_Hasync.push(['Histats.start', '1,3767234,4,4007,112,61,00011111']);
<?php endif; ?>
_Hasync.push(['Histats.fasi', '1']);
_Hasync.push(['Histats.track_hits', '']);
(function() {
var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
hs.src = ('//s10.histats.com/js15_as.js');
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
})();</script>
<?php if ($_SERVER['SERVER_NAME'] == $first_domain): ?>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?3755000&101" alt="" border="0"></a></noscript>
<?php elseif ($_SERVER['SERVER_NAME'] == $second_domain): ?>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?3767234&101" alt="" border="0"></a></noscript>
<?php endif; ?>
<!-- Histats.com  END  -->