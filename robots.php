<?php
header("Content-Type: text/plain");
echo "User-agent: *";
echo "\r\n";
echo "Sitemap: https://".$_SERVER['HTTP_HOST']."/sitemap.xml";