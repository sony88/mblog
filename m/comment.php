<?php
require_once(dirname(__FILE__)."/global.php");

if( !$loginStat )
{
	header("location:./?login");
}
else
{
	if( isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0 )
	{
		$DB = database();

		$msgId = $DB->fetch_one("SELECT `mid` FROM `".$mysql_prefix."blog` WHERE `mid` = ".$_GET['id']);

		if( !empty($msgId) )
		{
			$postResult = "";

			if( isset($_GET['delId']) && is_numeric($_GET['delId']) && $_GET['delId'] > 0 )
			{
				if( blogAction::delComment($msgId,$_GET['delId']) )
				{
					$postResult = "删除成功";
				}
			}

			if( isset($_POST['comment']) && $blog_config['comment_open'] > "0" )
			{
				$msg_con = filterCode($_POST['comment'],$blog_config['url_short']);

				if( empty($msg_con) || getStrlen($msg_con) > 70 )
				{
					$postResult = "输入不能为空且不超过70个字符";
				}
				else
				{
					if( blogAction::commentUpdate($msgId,"","",$msg_con) )
					{
						$postResult = "提交成功";
					}
					else
					{
						$postResult = "提交失败";
					}
				}
			}

			$getPage = (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 1) ? intval($_GET['p']) : 1;

			$commentArr = blogAction::getComment($msgId,$getPage,10);		

			$pageArr = pageAction::mobilePage($commentArr['Total'],10,$getPage);

			echo '<?xml version="1.0" encoding="UTF-8"?>';

			$tmp = template("comment.html",false);

			$tmp->assign( 'blogConfig',  $blog_config );

			$tmp->assign( 'postResult',  $postResult );

			$tmp->assign( 'messageId',  $msgId );

			$tmp->assign( 'thisPage',  $getPage );

			$tmp->assign( 'commentArr',  $commentArr );

			$tmp->assign( 'pageArr',  $pageArr );

			$tmp->assign( 'nowTime', date('H:i') );

			$tmp->output();
		}
		else
		{
			header("location:./");
		}

		$DB->close();
	}
	else
	{
		header("location:./");
	}
}
?>