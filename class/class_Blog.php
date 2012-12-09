<?php
class blogAction
{
	function blogUpdate($message,$picture,$origin)
	{
		global $DB,$mysql_prefix,$blog_config;
		
		$arr = array("message"=>$message,"picture"=>$picture,"dateline"=>time(),"origin"=>$origin,"comments"=>0);

		if( $DB->query($DB->insert_sql("`".$mysql_prefix."blog`",$arr)) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function commentUpdate($id,$name,$url,$msg,$display=1)
	{
		global $DB,$mysql_prefix,$loginStat;

		$arr = array("mid"=>$id,"nickname"=>$name,"blogurl"=>$url,"message"=>$msg,"dateline"=>time(),"blogmaster"=>$loginStat,"display"=>$display);

		if( $DB->query( $DB->insert_sql("`".$mysql_prefix."comment`",$arr) ) )
		{
			$DB->query( $DB->update_sql("`".$mysql_prefix."blog`",array("comments"=>array("`comments`+1")),"`mid`=".$id) );

			return true;
		}
		else
		{
			return false;
		}
	}

	function delBlog($mid,$pic)
	{
		global $DB,$mysql_prefix;

		$DB->query("DELETE FROM `".$mysql_prefix."blog` WHERE `mid`='".$mid."'");

		$DB->query("DELETE FROM `".$mysql_prefix."comment` WHERE `mid`='".$mid."'");

		delPicture($pic);

		return true;
	}

	function delComment($mid,$cid)
	{
		global $DB,$mysql_prefix;

		$delNum = $DB->affected_rows("DELETE FROM `".$mysql_prefix."comment` WHERE `mid`='".$mid."' AND `cid`='".$cid."'");

		if( $delNum > 0 )
		{
			$DB->query( $DB->update_sql("`".$mysql_prefix."blog`",array("comments"=>array("`comments`-1")),"`mid`='".$mid."'") );
		}

		return true;
	}

	function getBlog($page,$per)
	{
		global $DB,$mysql_prefix;

		$blogArr = array();

		$Total = $DB->fetch_one("SELECT COUNT(`mid`) FROM `".$mysql_prefix."blog`");

		if( $Total > 0 )
		{
			$lastPage = ceil($Total/$per);

			if( $page > $lastPage )
			{
				$page = $lastPage;
			}

			$Result = $DB->query("SELECT * FROM `".$mysql_prefix."blog` ORDER BY `mid` DESC LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Result))
			{
				$blogArr[] = array(
									"mid"		=> $Re['mid'],
									"message"	=> filterHTML($Re['message']),
									"picture"	=> $Re['picture'],
									"piclink"	=> str_replace("/s_","/b_",$Re['picture']),
									"dateline"	=> $Re['dateline'],
									"origin"	=> stripslashes($Re['origin']),
									"comments"	=> $Re['comments']
									);
			}
		}

		$return['Total'] = $Total;

		$return['Blog'] = $blogArr;
		
		return $return;
	}

	function getComment($mid,$page,$per)
	{
		global $DB,$mysql_prefix;

		$commentArr = array();
		
		if( $mid == 0 )
		{
			$where = "";
			
			$order = "DESC";
		}
		else
		{
			$where = " WHERE `mid`=".$mid;
			
			$order = "ASC";
		}

		$Total = $DB->fetch_one("SELECT COUNT(`cid`) FROM `".$mysql_prefix."comment`".$where);

		if( $Total > 0 )
		{
			$lastPage = ceil($Total/$per);

			if( $page > $lastPage )
			{
				$page = $lastPage;
			}

			$Rs = $DB->query("SELECT * FROM `".$mysql_prefix."comment`".$where." ORDER BY `cid` ".$order." LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Rs))
			{
				$commentArr[] = array(
									"cid"			=> $Re['cid'],
									"mid"			=> $Re['mid'],
									"nickname"		=> stripslashes($Re['nickname']),
									"message"		=> filterHTML($Re['message']),
									"dateline"		=> $Re['dateline'],
									"blogmaster"	=> $Re['blogmaster'],
									"display"		=> $Re['display']
									);
			}
		}

		$return['Total'] = $Total;

		$return['Comment'] = $commentArr;
		
		return $return;
	}

	function getStat($time)
	{
		global $DB,$mysql_prefix;

		return $DB->fetch_one("SELECT COUNT(`mid`) FROM `".$mysql_prefix."blog` WHERE `dateline` > ".$time);
	}

	function getFriend($where,$order,$page,$per)
	{
		global $DB,$mysql_prefix;

		$friendArr = array();

		$Total = $DB->fetch_one("SELECT COUNT(`fid`) FROM `".$mysql_prefix."friend` WHERE ".$where);

		if( $Total > 0 )
		{
			$lastPage = ceil($Total/$per);

			if( $page > $lastPage )
			{
				$page = $lastPage;
			}

			$Result = $DB->query("SELECT * FROM `".$mysql_prefix."friend` WHERE ".$where." ORDER BY ".$order." DESC LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Result))
			{
				$friendArr[] = array(
									"fid"			=> $Re['fid'],
									"furl"			=> $Re['furl'],
									"fupdate"		=> $Re['fupdate'],
									"friendavatar"	=> urlencode(base64_encode($Re['furl']."|||".$Re['friendavatar'])),
									"friendname"	=> stripslashes($Re['friendname']),
									"friendmid"		=> $Re['friendmid'],
									"friendmsg"		=> filterHTML($Re['friendmsg']),
									"friendpics"	=> empty($Re['friendpic']) ? "" : $Re['furl'].$Re['friendpic'],
									"friendpicb"	=> empty($Re['friendpic']) ? "" : $Re['furl'].str_replace("/s_","/b_",$Re['friendpic']),
									"friendpids"	=> urlencode(base64_encode($Re['furl'].$Re['friendpic'])),
									"friendpidb"	=> urlencode(base64_encode($Re['furl'].str_replace("/s_","/b_",$Re['friendpic']))),
									"friendtime"	=> $Re['friendtime'],
									"friendorigin"	=> stripslashes($Re['friendorigin'])
									);
			}
		}

		$return['Total'] = $Total;

		$return['Friend'] = $friendArr;
		
		return $return;
	}
}
?>