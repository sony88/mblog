<?php
require_once(dirname(__FILE__)."/global.php");

if( isset($_POST['username'],$_POST['password'],$_POST['newusername'],$_POST['newpassword']) )
{
	if( !$loginStat )
	{
		echo "0";
	}
	else
	{
		$curusername = filterCode($_POST['username']);

		$curpassword = filterCode($_POST['password']);

		$newusername = filterCode($_POST['newusername']);

		$newpassword = filterCode($_POST['newpassword']);

		if( $curusername != $admin_config['username'] || md5($curpassword) != $admin_config['password'] )
		{
			$errorInfo = "当前用户名与密码不匹配";
		}
		else
		{
			if( empty($newusername) && empty($newpassword) )
			{
				$errorInfo = "您需要修改什么？";
			}
			else
			{
				if( empty($newusername) )
				{
					$newusername = $admin_config['username'];
				}

				if( strlen($newusername) < 3 )
				{
					$errorInfo = "新用户名至少 3 个字符";
				}
				else
				{
					if( empty($newpassword) )
					{
						$newpassword = $admin_config['password'];
					}
					else
					{
						if( strlen($newpassword) < 6 )
						{
							$errorInfo = "新密码至少 6 位";
						}
						else
						{
							$newpassword = md5($newpassword);
						}
					}
				}
			}
		}

		if( isset($errorInfo) && !empty($errorInfo) )
		{
			echo $errorInfo;
		}
		else
		{
			$config_str = '$admin_config = array("username"=>"'.$newusername.'","password"=>"'.$newpassword.'","authcode"=>"'.createSecureKey().'");';

			$updateFile = updatePhpFile("database/config_admin.php",$config_str);

			if( $updateFile == "" )
			{
				echo "1";
			}
			else
			{
				echo $updateFile;
			}
		}
	}
}
else
{
	if( !$loginStat )
	{
		header("location:./login.php");
	}
	else
	{
		$tmp = template("password.html");

		$tmp->assign( 'blogConfig', $blog_config );

		$tmp->assign( 'adminConfig', $admin_config );

		$tmp->assign( 'loginStat', $loginStat );

		$tmp->output();
	}
}

ob_end_flush();
?>