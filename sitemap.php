<?php
header('Content-type: application/xml');
header('Pragma: public');
header('Cache-Control: private');
header('Expires: -1');
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/</loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ja'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?lang=vi</loc>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','vi'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?lang=en</loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','en'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?lang=ru</loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ru'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?lang=es</loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','es'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?lang=zh</loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','zh'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?lang=ja</loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/','ja'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/bmi/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/xemngay/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/game/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/proverbs/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/what-is-biorhythm/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/nhip-sinh-hoc-la-gi/</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?p=bmi</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?p=lunar</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?p=pong</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?p=race</loc>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
<?php
$users = load_all_array('nsh_users');
usort($users,'sort_name_ascend');
$count = count($users);
for ($i = 0; $i < $count; ++$i):
?>
	<url>
		<loc>http://<?php echo $_SERVER['HTTP_HOST']; ?>/?fullname=<?php echo str_replace(' ','+',$users[$i]['name']); ?>&amp;dob=<?php echo $users[$i]['dob']; ?></loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?></loc>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?></loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?></loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?></loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
	</url>
	<url>
		<loc>http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?></loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
	<url>
		<loc>http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?></loc>
		<xhtml:link rel="alternate" hreflang="vi" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'vi'); ?>"/>
		<xhtml:link rel="alternate" hreflang="en" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'en'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ru" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ru'); ?>"/>
		<xhtml:link rel="alternate" hreflang="es" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'es'); ?>"/>
		<xhtml:link rel="alternate" hreflang="zh" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'zh'); ?>"/>
		<xhtml:link rel="alternate" hreflang="ja" href="http://<?php echo change_url_lang($_SERVER['HTTP_HOST'].'/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'],'ja'); ?>"/>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
		<mobile:mobile/>
	</url>
<?php
endfor;
?>
</urlset>