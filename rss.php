<?php
error_reporting(E_ALL);

header("Content-Type: text/xml");

require_once(dirname(__FILE__)."/database/config_site.php");

require_once(dirname(__FILE__)."/database/config_mysql.php");

require_once(dirname(__FILE__)."/class/class_Blog.php");

require_once(dirname(__FILE__)."/include/function.php");

$getPage = (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 1) ? intval($_GET['p']) : 1;

$DB = database();

$blogArr = BlogAction::getBlog($getPage,50);

$DB->close();

unset($DB);

echo '<?xml version="1.0" encoding="utf-8"?>';

echo '<rss version="2.0">';

echo "<channel>";

echo "<title><![CDATA[".stripslashes($blog_config['sitename'])."]]></title>";

echo "<link>".$blog_config['siteurl']."</link>";

echo "<description><![CDATA[".stripslashes(strip_tags($blog_config['siteintro']))."]]></description>";

echo "<generator>".stripslashes($blog_config['nickname'])."</generator>";

echo "<language>zh-cn</language>";

echo "<copyright>Copyright 2011 Powerd By PHPSay-Microblog</copyright>";

echo "<image>";

echo "<url>".$blog_config['siteurl']."/".$blog_config['avatar_upload']."avatar.jpg</url>";

echo "</image>";

for($i=0;$i<count($blogArr['Blog']);$i++)
{
	$arr = $blogArr['Blog'][$i];

	echo "<item>";

	echo "<title><![CDATA[".strip_tags($arr['message'])."]]></title>";

	echo "<link>".$blog_config['siteurl']."</link>";

	echo "<pubDate>".date('r',$arr['dateline'])."</pubDate>";

	echo "<description><![CDATA[";

	if( $arr['picture'] != "" )
	{
		echo "<a href=".$blog_config['pic_upload'].$arr['piclink']."><img src=".$blog_config['pic_upload'].$arr['picture']." border=0></a>";
	}
	
	echo "]]></description>";

	echo "</item>";
}

echo "</channel>";

echo "</rss>";
?>