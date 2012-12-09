<?php
require_once(dirname(__FILE__)."/database/config_site.php");

require_once(dirname(__FILE__)."/database/config_admin.php");

require_once(dirname(__FILE__)."/database/config_mysql.php");

require_once(dirname(__FILE__)."/class/class_Xxtea.php");

require_once(dirname(__FILE__)."/include/function.php");

if( isset($_GET['img']) && !empty($_GET['img']) )
{
	$imgUrl = base64_decode($_GET['img']);

	$fileType = strtolower(strrchr($imgUrl,"."));

	if ( in_array($fileType, array(".jpg",".jpeg",".gif",".png")) )
	{
		$imgStream = getFromUrl($imgUrl);

		if( $imgStream )
		{
			if( $fileType == ".jpg" || $fileType == ".jpeg" )
			{
				header("Content-type: image/jpeg");
			}

			if( $fileType == ".gif" )
			{
				header("Content-type: image/gif");
			}

			if( $fileType == ".png" )
			{
				header("Content-type: image/png");
			}

			echo $imgStream;
		}
		else
		{
			header("Content-type: image/png");

			$im = imagecreate(145, 25);
			
			$background_color = imagecolorallocate($im, 255, 255, 255);
			
			$text_color = imagecolorallocate($im, 233, 14, 91);
			
			imagestring($im, 4, 5, 5,  "Picture Not Found", $text_color);
			
			imagepng($im);
			
			imagedestroy($im);
		}
	}

	exit;
}

if( isset($_GET['avatar']) && !empty($_GET['avatar']) )
{
	$avatarArr = explode("|||",base64_decode($_GET['avatar']));

	$saveName = $blog_config['avatar_upload'].md5($avatarArr[0]).".jpg";

	$updateAvatar = false;

	if( !file_exists($saveName) )
	{
		$updateAvatar = true;
	}
	else
	{
		if( ( time()-filemtime($saveName) ) > 172800 )
		{
			$updateAvatar = true;
		}
	}

	if( $updateAvatar )
	{
		$avatar = @getimagesize($avatarArr[0].$avatarArr[1]);

		if( isset($avatar[0],$avatar[1],$avatar[2]) && $avatar[0] == 50 && $avatar[1] == 50 && $avatar[2] == 2 )
		{
			$avatarStream = getFromUrl($avatarArr[0].$avatarArr[1]);

			if( !empty($avatarStream) )
			{
				if( file_exists($saveName) )
				{
					@unlink($saveName);
				}
									
				$image = imagecreatefromstring($avatarStream);

				if ( $image !== false )
				{
					imagejpeg($image,$saveName,100);

					imagedestroy($image);
				}
			}
		}
	}

	header("location:".$saveName);

	exit;
}

if( isset($_GET['do']) && $_GET['do'] == "update" )
{
	set_time_limit(0);

	$DB = database();

	$Rs = $DB->query("SELECT `furl` FROM `".$mysql_prefix."friend` WHERE `ftype` > 0 AND `fupdate` < ".(time()-300)." GROUP BY `furl`");

	while($Re = $DB->fetch_array($Rs))
	{
		$getInfo = getFromUrl($Re['furl']."friend_server.php?do=get",2);

		if( !empty($getInfo) )
		{
			$fArr = json_decode($getInfo,true);

			if( isset($fArr['nickname'],$fArr['avatar'],$fArr['mid'],$fArr['message'],$fArr['picture'],$fArr['dateline'],$fArr['origin']) )
			{
				$nowTime = time();

				$updateArr = array(
								"fupdate"		=> $nowTime,
								"friendavatar"	=> $fArr['avatar'],
								"friendname"	=> addslashes($fArr['nickname']),
								"friendmid"		=> $fArr['mid'],
								"friendmsg"		=> addslashes($fArr['message']),
								"friendpic"		=> $fArr['picture'],
								"friendtime"	=> ($fArr['dateline'] < $nowTime) ? $fArr['dateline'] : $nowTime,
								"friendorigin"	=> addslashes($fArr['origin'])
								);

				$DB->query( $DB->update_sql("`".$mysql_prefix."friend`",$updateArr,"`ftype` > 0 AND `furl`='".$Re['furl']."'") );
			}
		}
	}

	$DB->close();

	unset($DB);
}

if( isset($_GET['do']) && $_GET['do'] == "get" )
{
	$DB = database();

	$Re = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."blog` ORDER BY `mid` DESC LIMIT 1");

	$DB->close();

	unset($DB);

	$blogArr = array(
					"avatar"	=>	$blog_config['avatar_upload']."avatar.jpg",
					"nickname"	=>	stripslashes($blog_config['nickname']),
					"mid"		=>	$Re['mid'],
					"message"	=>	stripslashes($Re['message']),
					"picture"	=>	empty($Re['picture']) ? "" : $blog_config['pic_upload'].$Re['picture'],
					"dateline"	=>	$Re['dateline'],
					"origin"	=>	stripslashes($Re['origin'])
					);

	echo json_encode($blogArr);
}

if( isset($_GET['do'],$_POST['url']) && $_GET['do'] == "addFollow" )
{
	if( !isLogin() )
	{
		echo "0";
	}
	else
	{
		$fUrl = filterCode($_POST['url']);

		if( substr($fUrl,0,7) != "http://" || substr($fUrl,-1) != "/" )
		{
			echo "2";
		}
		else
		{
			if( $fUrl == $blog_config['siteurl']."/" )
			{
				echo "3";
			}
			else
			{
				$DB = database();

				if( $DB->fetch_one("SELECT COUNT(`fid`) FROM `".$mysql_prefix."friend` WHERE `ftype`=1 AND `furl`='".$fUrl."'") != 0 )
				{
					echo "4";
				}
				else
				{
					$nickName = urlencode($blog_config['nickname']);

					$mblogUrl = urlencode($blog_config['siteurl']."/");

					$secureCode = createSecureKey();

					$checkFriend = getFromUrl($fUrl."friend_server.php?do=add&name=".$nickName."&url=".$mblogUrl."&code=".$secureCode);

					$backCode = substr($checkFriend,0,2);

					$backInfo = substr($checkFriend,2);

					if( ($backCode != "0," && $backCode != "1," ) || empty($backInfo) )
					{
						echo "5";
					}
					else
					{
						if( $backInfo == "infoError" )
						{
							echo "6";
						}
						else
						{
							if( $backInfo == "backError" )
							{
								echo "7";
							}
							else
							{
								$checkInfo = checkName($backInfo);

								if( $backCode == "1," && empty($checkInfo) )
								{
									$dbArr = array(
													"ftype"			=>	1,
													"furl"			=>	$fUrl,
													"fcode"			=>	$secureCode,
													"fupdate"		=>	0,
													"friendavatar"	=>	"",
													"friendname"	=>	$backInfo,
													"friendmid"		=>	0,
													"friendmsg"		=>	"",
													"friendpic"		=>	"",
													"friendtime"	=>	0,
													"friendorigin"	=>	""
													);

									$DB->query( $DB->insert_sql("`".$mysql_prefix."friend`",$dbArr) );

									echo "1";
								}
								else
									echo "8";
							}
						}
					}
				}

				$DB->close();
			}
		}
	}
}

if( isset($_GET['do'],$_POST['url']) && $_GET['do'] == "delFollow" )
{
	if( isLogin() )
	{
		$DB = database();

		$fCode = $DB->fetch_one("SELECT `fcode` FROM `".$mysql_prefix."friend` WHERE `ftype`=1 AND `furl`='".$_POST['url']."'");

		$DB->query("DELETE FROM `".$mysql_prefix."friend` WHERE `ftype`=1 AND `furl`='".$_POST['url']."'");

		$getFans = $DB->fetch_one("SELECT COUNT(`fid`) FROM `".$mysql_prefix."friend` WHERE `ftype`=2 AND `furl`='".$_POST['url']."'");
		
		$DB->close();

		if( $getFans == 0 )
		{
			if( file_exists($blog_config['avatar_upload'].md5($_POST['url']).".jpg") )
			{
				@unlink($blog_config['avatar_upload'].md5($_POST['url']).".jpg");
			}
		}

		$delFollow = getFromUrl($_POST['url']."friend_server.php?do=del&url=".urlencode($blog_config['siteurl']."/")."&code=".$fCode);
		
		echo "1";
	}
}

if( isset($_GET['do'],$_POST['url']) && $_GET['do'] == "delFans" )
{
	if( isLogin() )
	{
		$DB = database();

		$DB->query("DELETE FROM `".$mysql_prefix."friend` WHERE `ftype`=2 AND `furl`='".$_POST['url']."'");

		$getFollow = $DB->fetch_one("SELECT COUNT(`fid`) FROM `".$mysql_prefix."friend` WHERE `ftype`=1 AND `furl`='".$_POST['url']."'");
		
		$DB->close();

		if( $getFollow == 0 )
		{
			if( file_exists($blog_config['avatar_upload'].md5($_POST['url']).".jpg") )
			{
				@unlink($blog_config['avatar_upload'].md5($_POST['url']).".jpg");
			}
		}
		
		echo "1";
	}
}

if( isset($_GET['do'],$_GET['info'],$_GET['code']) && $_GET['do'] == "check" )
{
	$infoStr = Xxtea::decrypt($_GET['info'],$_GET['code']);

	$infoArr = explode("@|PHPSay-Microblog|@",$infoStr);

	if( isset($infoArr[0],$infoArr[1]) && $infoArr[0] == $blog_config['nickname'] && $infoArr[1] == $blog_config['siteurl']."/" )
	{
		$secureCode = createSecureKey();

		$secureStr = Xxtea::encrypt($infoStr,$secureCode);

		echo json_encode(array($secureStr,$secureCode));
	}
}

if( isset($_GET['do'],$_GET['name'],$_GET['url'],$_GET['code']) && $_GET['do'] == "add" )
{
	$fName = filterCode($_GET['name']);

	$fUrl = filterCode($_GET['url']);

	$fCode = filterCode($_GET['code']);

	$checkName = checkName($fName);

	if( !empty($checkName) || substr($fUrl,0,7) != "http://" || substr($fUrl,-1) != "/" || $fUrl == $blog_config['siteurl']."/" || strlen($fCode) != 16 )
	{
		echo "0,infoError";
	}
	else
	{
		$secureKey = createSecureKey();

		$secureValue = $fName."@|PHPSay-Microblog|@".$fUrl;

		$secureInfo = Xxtea::encrypt($secureValue,$secureKey);

		$checkFriend = getFromUrl($fUrl."friend_server.php?do=check&info=".urlencode($secureInfo)."&code=".$secureKey);

		if( !empty($checkFriend) )
		{
			$backArr = json_decode($checkFriend,true);

			if( is_array($backArr) && isset($backArr[0],$backArr[1]) )
			{
				if( Xxtea::decrypt($backArr[0],$backArr[1]) == $secureValue )
				{
					$checkFriend = 1;
				}
				else
					$checkFriend = 0;
			}
			else
				$checkFriend = 0;
		}

		if( $checkFriend != 1 )
		{
			echo "0,backError";
		}
		else
		{
			$DB = database();

			$dbArr = array(
							"ftype"			=>	2,
							"furl"			=>	$fUrl,
							"fcode"			=>	$fCode,
							"fupdate"		=>	0,
							"friendavatar"	=>	"",
							"friendname"	=>	$fName,
							"friendmid"		=>	0,
							"friendmsg"		=>	"",
							"friendpic"		=>	"",
							"friendtime"	=>	0,
							"friendorigin"	=>	""
							);

			if( $DB->fetch_one("SELECT COUNT(`fid`) FROM `".$mysql_prefix."friend` WHERE `ftype`=2 AND `furl`='".$fUrl."'") == 0 )
			{
				$DB->query( $DB->insert_sql("`".$mysql_prefix."friend`",$dbArr) );
			}
			else
			{
				$DB->query( $DB->update_sql("`".$mysql_prefix."friend`",$dbArr,"`ftype`=2 AND `furl`='".$fUrl."'") );
			}

			$DB->close();

			unset($DB);

			echo "1,".$blog_config['nickname'];
		}
	}
}

if( isset($_GET['do'],$_GET['url'],$_GET['code']) && $_GET['do'] == "del" )
{
	$DB = database();

	$DB->query("DELETE FROM `".$mysql_prefix."friend` WHERE `ftype`=2 AND `furl`='".$_GET['url']."' AND `fcode`='".$_GET['code']."'");

	$DB->close();

	unset($DB);
}
?>