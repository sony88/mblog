<?php
require_once(dirname(__FILE__)."/global.php");

if( isset($_POST['comment'],$_POST['mid']) )
{
	if( isset($_POST['furl']) )
	{
		if( !$loginStat )
		{
			echo "0 请先登录";
		}
		else
		{
			$postArr = array(
							"nickname"	=>	stripslashes($blog_config['nickname']),
							"blogurl"	=>	stripslashes($blog_config['siteurl']),
							"comment"	=>	stripslashes($_POST['comment']),
							"mid"		=>	$_POST['mid']
							);
				
			$returnInfo = postToUrl($_POST['furl']."comment.php",$postArr,$blog_config['siteurl']);

			if( empty($returnInfo) )
			{
				echo "0 连接失败";
			}
			else
			{
				echo $returnInfo;
			}
		}
	}
	else
	{
		if( $blog_config['comment_open'] == "0" )
		{
			echo "0 禁止评论";
		}
		else
		{
			$nickname = "";

			$blogurl = "";

			$display = 1;

			if( !$loginStat )
			{
				if( isset($_POST['nickname']) )
				{
					$nickname = trim($_POST['nickname']);

					if( !empty($nickname) )
					{
						$checkName = checkName($nickname);

						if( !empty($checkName) )
						{
							echo "-1 ".$checkName;

							ob_end_flush();

							exit;
						}
					}
				}

				if( isset($_POST['blogurl'],$_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == $_POST['blogurl'] )
				{
					if( strlen($_POST['blogurl']) <= 60 )
					{
						$blogurl = $_POST['blogurl'];
					}
				}

				$display = ( $blog_config['comment_open'] == "1" ) ? 1 : 0;
			}

			$msg_con = filterCode($_POST['comment'],$blog_config['url_short']);

			if( empty($msg_con) || getStrlen($msg_con) > 70 )
			{
				echo "0 评论不能为空且不超过70个字符";
			}
			else
			{
				$DB = database();

				$blogId = $DB->fetch_one("SELECT `mid` FROM `".$mysql_prefix."blog` WHERE `mid`='".$_POST['mid']."'");

				if( empty($blogId) )
				{
					echo "0 评论被拒绝";
				}
				else
				{
					if( blogAction::commentUpdate($blogId,$nickname,$blogurl,$msg_con,$display) )
					{
						if( $display )
						{
							$commentNum = $DB->fetch_one("SELECT COUNT(`cid`) FROM `".$mysql_prefix."comment` WHERE `mid`=".$blogId);

							$lastPage = ceil($commentNum/$blog_config['per_comment']);

							echo "1 ".$lastPage;
						}
						else
						{
							echo "2 评论成功 审核后可见";
						}
					}
					else
					{
						echo "0 评论失败";
					}
				}

				$DB->close();

				unset($DB);
			}
		}
	}
}

if( isset($_POST['messageId'],$_POST['deleteId']) )
{
	if( $loginStat )
	{
		$DB = database(false);

		if( blogAction::delComment($_POST['messageId'],$_POST['deleteId']) )
		{
			echo "1";
		}

		$DB->close();

		unset($DB);
	}
}

if( isset($_POST['displayId']) )
{
	if( $loginStat )
	{
		$DB = database(false);

		if( $DB->query($DB->update_sql("`".$mysql_prefix."comment`",array("display"=>1),"`cid`='".$_POST['displayId']."'")) )
		{
			echo "1";
		}

		$DB->close();

		unset($DB);
	}
}

if( isset($_POST['id'],$_POST['pg']) )
{
	$msgId = intval($_POST['id']);

	$pgNum = intval($_POST['pg']);

	$DB = database();

	$commentArr = blogAction::getComment($msgId,$pgNum,$blog_config['per_comment']);

	$DB->close();

	unset($DB);

	$pageArr = pageAction::commentPage($msgId,$commentArr['Total'],$blog_config['per_comment'],$pgNum);

	$tmp = template("comment.html");

	$tmp->assign( 'blogConfig',  $blog_config );

	$tmp->assign( 'loginStat',  $loginStat );

	$tmp->assign( 'messageId',  $msgId );

	$tmp->assign( 'commentArr',  $commentArr );

	$tmp->assign( 'pageArr',  $pageArr );

	$tmp->output();

	unset($tmp);
}

ob_end_flush();
?>