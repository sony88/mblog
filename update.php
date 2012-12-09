<?php
header("Content-Type: text/xml");

require_once(dirname(__FILE__)."/database/config_site.php");

require_once(dirname(__FILE__)."/database/config_mysql.php");

require_once(dirname(__FILE__)."/database/config_admin.php");

require_once(dirname(__FILE__)."/database/config_sync.php");

require_once(dirname(__FILE__)."/class/class_Blog.php");

require_once(dirname(__FILE__)."/include/function.php");

if( !isset($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']) )
{
	header('WWW-Authenticate: Basic realm="PHPSay-Microblog API"');

	header('HTTP/1.0 401 Unauthorized');
		
	die('<error>Could not authenticate you.</error>');
}

if( $_SERVER['PHP_AUTH_USER'] != $admin_config['username'] || md5($_SERVER['PHP_AUTH_PW']) != $admin_config['password'] )
{
	header('WWW-Authenticate: Basic realm="PHPSay-Microblog API"');
		
	header('HTTP/1.0 401 Unauthorized');
		
	die('<error>Could not authenticate you.</error>');
}

$updateRes = false;

if( isset($_POST['status']) )
{
	$msg_con = filterCode($_POST['status'],$blog_config['url_short']);

	$msg_len = getStrlen($msg_con);

	if( $msg_len >= 1 && $msg_len <= 140 )
	{
		$DB = database();

		$updateRes = blogAction::blogUpdate($msg_con,"","API");

		$DB->close();

		unset($DB);
	}
}

if( $updateRes )
{
	echo '<update type="bool">true</update>';
}
else
{
	echo '<update type="bool">false</update>';
}
?>