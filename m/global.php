<?php
if( isset($_SERVER['HTTP_USER_AGENT']) )
{
	if( preg_match('/Windows NT/i',$_SERVER['HTTP_USER_AGENT']) )
	{
		header("location:../");

		exit;
	}
}

header("content-Type:application/xhtml+xml; charset=utf-8");

require_once(dirname(__FILE__)."/../database/config_site.php");

require_once(dirname(__FILE__)."/../database/config_mysql.php");

require_once(dirname(__FILE__)."/../database/config_admin.php");

require_once(dirname(__FILE__)."/../class/class_Blog.php");

require_once(dirname(__FILE__)."/../class/class_Page.php");

require_once(dirname(__FILE__)."/../class/class_Xxtea.php");

require_once(dirname(__FILE__)."/../include/function.php");

$loginStat = (isLogin()) ? 1 : 0;
?>