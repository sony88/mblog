<?php
require_once(dirname(__FILE__)."/global.php");

if( isset($_GET['do']) && $_GET['do'] == "logout" )
{
	loginOut();

	header("location:./");
}
else
{
	if( $loginStat )
	{
		header("location:./");
	}
	else
	{
		$loginResult = "";

		if( isset($_POST['username'],$_POST['password']) )
		{
			$username = filterCode($_POST['username']);

			$password = filterCode($_POST['password']);

			if( loginCheck($username,$password) )
				$loginResult = "ok";
			else
				$loginResult = "error";
		}

		$tmp = template("login.html");

		$tmp->assign( 'blogConfig',  $blog_config );

		$tmp->assign( 'loginResult',  $loginResult );

		$tmp->output();
	}
}

ob_end_flush();
?>