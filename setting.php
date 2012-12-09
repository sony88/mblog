<?php
require_once(dirname(__FILE__)."/global.php");

if( isset($_POST['version'],$blog_config['version']) && $_POST['version'] == $blog_config['version'] )
{
	if( !$loginStat )
	{
		echo "0";
	}
	else
	{
		$config_str = '$blog_config = array(';

		foreach( $_POST as $key => $val )
		{
			if( isset($blog_config[$key]) )
			{
				if( $key == "nickname" )
				{
					$error_info = checkName($val);

					if( !empty($error_info) )
					{
						break;
					}
				}

				if( $key == "siteurl" )
				{
					if( substr($val,0,7) != "http://" || substr($val,-1) == "/" )
					{
						$error_info = "微博地址请以 http:// 开头，结尾不要加 /";

						break;
					}
				}

				if( $key == "siteintro" )
				{
					$val = strAddslashes(trim($val));
				}
				else if( $key == "tracking_code" )
				{
					$val = str_replace(array("\r","\n"),"",strAddslashes(trim($val)));
				}
				else
				{
					$val = filterCode(htmlspecialchars($val));
				}

				if( is_numeric($val) || $val == "true" || $val == "false" )
				{
					$config_str .= "\n\"".$key."\" => ".$val.",";
				}
				else
					$config_str .= "\n\"".$key."\" => \"".$val."\",";
			}
		}

		/* Start Delete _cache */

		$dh = opendir("_cache/");

		while( $file = readdir($dh) )
		{
			if( substr($file,-9) == "_html.php" )
			{
				unlink("_cache/".$file);
			}
		}

		closedir($dh);

		/* End Delete _cache */

		if( isset($error_info) && !empty($error_info) )
		{
			echo $error_info;
		}
		else
		{
			$config_str = substr($config_str,0,-1).");\n";

			if( !empty($_POST['timezone']) )
			{
				$config_str .= 'ini_set(\'date.timezone\',\''.$_POST['timezone'].'\');';
			}

			$updateFile = updatePhpFile("database/config_site.php",$config_str);

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
		$skinArr = array();

		$source = "./_template/";

		$handle = opendir($source);

		while( ($file = readdir($handle)) !== false )
		{
			if( $file != "." && $file != ".." )
			{
				if( is_dir($source.DIRECTORY_SEPARATOR.$file) )
				{
					$skinArr[] = array( "skin" => $file );
				}
			}
		}

		closedir($handle);

		$tmp = template("setting.html");

		$tmp->assign( 'blogConfig', $blog_config );

		$tmp->assign( 'trackingCode', htmlspecialchars(stripslashes($blog_config['tracking_code'])) );

		$tmp->assign( 'loginStat', $loginStat );

		$tmp->assign( 'skinArr', $skinArr );

		$tmp->output();
	}
}

ob_end_flush();
?>