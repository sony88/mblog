<?php
error_reporting(E_ALL);

header("Content-Type:text/html;charset=utf-8");

if ( ini_get( 'zlib.output_compression' ) )
{
	if ( ini_get( 'zlib.output_compression_level' ) != 5)
	{
		ini_set( 'zlib.output_compression_level', '5' );
	}
	ob_start();
}
else
{
	if( isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'],"gzip") )
	{
		ob_start("ob_gzhandler");
	}
	else
	{
		ob_start();
	}
}

require_once(dirname(__FILE__)."/database/config_site.php");

require_once(dirname(__FILE__)."/database/config_mysql.php");

require_once(dirname(__FILE__)."/database/config_admin.php");

require_once(dirname(__FILE__)."/class/class_Blog.php");

require_once(dirname(__FILE__)."/class/class_Page.php");

require_once(dirname(__FILE__)."/class/class_Xxtea.php");

require_once(dirname(__FILE__)."/include/function.php");

$loginStat = (isLogin()) ? 1 : 0;
?>