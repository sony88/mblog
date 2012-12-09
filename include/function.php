<?php
function template($htmlFile,$skinName=true)
{
	global $blog_config;

	require_once(dirname(__FILE__)."/../class/class_Template.php");

	if( $skinName )
	{
		$htmlFile = $blog_config['skin']."/".$htmlFile;
	}

	$template  =  new phpSayTemplate($htmlFile);

	return $template;
}

function database($setNames=true)
{
	require_once(dirname(__FILE__)."/../class/class_Mysql.php");

	global $mysql_host,$mysql_user,$mysql_pass,$mysql_dbname;

	$DB = new DB_MySQL;

	$DB->connect($mysql_host,$mysql_user,$mysql_pass,$mysql_dbname);

	if($setNames)
	{
		$DB->query("SET NAMES 'utf8'");
	}

	return $DB;
}

function isLogin($secure="")
{
	global $admin_config;

	if( empty($secure) )
	{
		if( isset($_COOKIE['adminSecure']) )
		{
			$secure = $_COOKIE['adminSecure'];
		}
	}

	if( !empty($secure) )
	{
		$arr = explode("|",Xxtea::decrypt($secure,$admin_config['authcode']));

		if( isset($arr[0],$arr[1]) )
		{
			if( $admin_config['username'] == $arr[0] && $admin_config['password'] == $arr[1] )
			{
				return true;
			}
		}
	}

	return false;
}

function loginOut()
{
	setcookie("adminSecure",'',time()-3600,"/");
}

function loginCheck($username,$password)
{
	global $admin_config;

	if( $admin_config['username'] == $username && $admin_config['password'] == md5($password) )
	{
		$secure = Xxtea::encrypt($admin_config['username']."|".$admin_config['password'],$admin_config['authcode']);
			
		setcookie("adminSecure",$secure,time()+86400,"/");

		return true;
	}
	else
	{
		return false;
	}
}

function wordCheck($str)
{
	$bArr = explode("_","!_@_#_\$_%_^_&_*_(_)_._-_+_=_ˇ_¨_·_/_\_<_>_?_{_}_[_]_|_,_　_ __。_；_：_？_，_;_:_'_\"_~_`");

	for($i=0;$i<count($bArr);$i++)
	{
		if( strpos( "phpsay".$str,$bArr[$i] ) )
		{
			return false;
		}
	}

	return true;
}

function checkName($username)
{
	$username_len = getStrlen($username);

	if( $username_len < 2 )
	{
		return "昵称至少2个字符。";
	}

	if( !wordCheck($username) )
	{
		return "昵称不能含有非法字符。";
	}

	if( preg_match("/^[\x7f-\xff]+$/",$username) )
	{
		if( $username_len > 7 )
		{
			return "昵称不能超过7个汉字。";
		}
	}
	else if( preg_match("/^[0-9a-zA-Z\_]*$/",$username) )
	{
		if( $username_len > 10 )
		{
			return "昵称请不要超出10个字符";
		}
	}
	else
	{
		if( $username_len > 8 )
		{
			return "您的昵称太长啦 ^_^";
		}
	}

	return "";
}

function createSecureKey($len=16)
{
	$chararr = array('0','1','2','3','4','5','6','7','8','9','~','a','b','C','d','e','f','h','j','K','L','M','n','P','Q','R','s');

	$keyindex = count($chararr)-1;
	
	$keystr = "";
	
	for ( $i=0;$i<$len;$i++ )
	{
		$keystr .= $chararr[rand(0,$keyindex)];
	}

	return $keystr;
}

function getFromUrl($url,$timeout=5)
{
	$opts = array("http"=>array("header"=>"Referer:".$url,"method"=>"GET","timeout"=>$timeout));
	
	$context = stream_context_create($opts);

	$html = @file_get_contents($url, false, $context);

	return $html;
}

function postToUrl($url,$arr,$referer,$timeout=5)
{
	$opts = array(
					"http"=>array(
									"header"=>"Referer:".$referer,
									"method"=>"POST",
									'content' => http_build_query($arr, '', '&'),
									"timeout"=>$timeout
									)
					);
	
	$context = stream_context_create($opts);

	$html = @file_get_contents($url, false, $context);

	return $html;
}

function strAddslashes($str)
{
	if ( !get_magic_quotes_gpc() )
	{
		$str = addslashes($str);
	}

	return $str;
}

function parseUrl($str)
{
	$auto_url = "/(?<=[^\]a-z0-9-=\"'\\/])((https?):\/\/)([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i";

	$auto_link = '<a href="\\1\\3" target="_blank">\\1\\3</a>';

	return preg_replace($auto_url,$auto_link," ".$str);
}

function shortenUrl($match)
{
	$newUrl = getFromUrl("http://tinyurl.com/api-create.php?url=".$match[1]);

	if( empty($newUrl) )
	{
		$newUrl = $match[1];
	}

	return str_replace($match[1],$newUrl,$match[0]);
}

function filterCode($str,$shortenUrl=false)
{
	if( $shortenUrl )
	{
		$str = parseUrl($str);

		$str = preg_replace_callback('/<a href="(.*?)" target="_blank">(.*?)<\/a>/i','shortenUrl',$str);
	}

	$str = str_replace(array("\r","\n"),"",$str);

	$str = strip_tags($str);

	$str = strAddslashes($str);

	return trim($str);
}

function filterHTML($str)
{
	global $blog_config;

	$str = htmlspecialchars($str);

	$str = stripslashes($str);

	$str = parseUrl($str);

	$str = preg_replace("@\@".$blog_config['nickname']."([,|.| |;|:]+)@is", "@<strong>".$blog_config['nickname']."</strong>\\1", $str);
	
	return trim($str);
}

function getStrlen($str)
{
	if( function_exists('mb_strlen') )
	{
		return mb_strlen($str,"utf-8");
	}
	else
	{
		return preg_match_all('%(?:[\x09\x0A\x0D\x20-\x7E]
								| [\xC2-\xDF][\x80-\xBF]
								| \xE0[\xA0-\xBF][\x80-\xBF] 
								| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
								| \xED[\x80-\x9F][\x80-\xBF]
								| \xF0[\x90-\xBF][\x80-\xBF]{2}
								| [\xF1-\xF3][\x80-\xBF]{3}
								| \xF4[\x80-\x8F][\x80-\xBF]{2})%xs',$str,$out);
	}
}

function Truncate($string,$len,$wordsafe=FALSE)
{
	$slen = strlen($string);

	if ($slen <= $len)
	{
		return $string;
	}
	
	if ($wordsafe)
	{
		while (($string[--$len] != ' ') && ($len > 0)) {};
	}
	
	if ((ord($string[$len]) < 0x80) || (ord($string[$len]) >= 0xC0))
	{
		return substr($string, 0, $len)."..";
	}
	
	while (ord($string[--$len]) < 0xC0) {};
	
	return substr($string, 0, $len)."..";
}

function mkDirs($path)
{
	$array_path = explode("/",$path);

	$_path = "";
		
	for($i=0;$i<count($array_path);$i++)
	{
		$_path .= $array_path[$i]."/";

		if( !empty($array_path[$i]) && !file_exists($_path))
		{
			mkdir($_path,0777);
		}
	}
	
	return true;
}

function updatePhpFile($phpFile,$phpStr,$phpTag=true)
{
	$return = "";

	if( $phpTag )
		$phpStr = "<?php\n".$phpStr."\n?>";

	if( file_exists($phpFile) )
	{
		if( is_writable($phpFile) )
		{
			$handle = fopen($phpFile, 'w');

			if( !$handle )
			{
				$return = $phpFile." 文件无法打开";
			}
			else
			{
				if ( flock($handle, LOCK_EX) )
				{
					if( fwrite($handle, $phpStr) === FALSE )
					{
						$return = "不能写入到文件 ".$phpFile;
					}

					flock($handle, LOCK_UN);
				}
				else
					$return = $phpFile." 文件无法锁定";
					
				fclose($handle);
			}
		}
		else
			$return = $phpFile." 文件不可写";
	}
	else
		$return = $phpFile." 文件不存在";

	return $return;
}

function delPicture($deletePic)
{
	global $blog_config;

	if( !empty($deletePic) )
	{
		$deletePic = str_replace("\\","/",dirname(__FILE__))."/../".$blog_config['pic_upload'].$deletePic;

		if( file_exists($deletePic) )
		{
			@unlink($deletePic);
		}

		$deletePic = str_replace("/s_","/b_",$deletePic);

		if( file_exists($deletePic) )
		{
			@unlink($deletePic);
		}
	}
}

function createImg($oldImg,$newImg,$imgInfo,$maxWidth=200,$maxHeight=200)
{
	if( $maxWidth > $imgInfo[0] || $maxHeight > $imgInfo[1] )
	{
		$maxWidth = $imgInfo[0];

		$maxHeight = $imgInfo[1];
	}
	else
	{
		if ( $imgInfo[0] < $imgInfo[1] )
			$maxWidth = ($maxHeight / $imgInfo[1]) * $imgInfo[0];
		else
			$maxHeight = ($maxWidth / $imgInfo[0]) * $imgInfo[1];
	}

	$image_p = imagecreatetruecolor($maxWidth, $maxHeight);

	switch($imgInfo[2])
	{
		case 1:
			$image = imagecreatefromgif($oldImg);
			break;
		case 2:
			$image = imagecreatefromjpeg($oldImg);
			break;
		case 3:
			$image = imagecreatefrompng($oldImg);
		break;
	}

	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $maxWidth, $maxHeight, $imgInfo[0], $imgInfo[1]);

	imagejpeg($image_p, $newImg,100);

	imagedestroy($image_p);

	imagedestroy($image);

	return true;
}
?>