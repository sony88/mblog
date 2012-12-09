<?php
require_once(dirname(__FILE__)."/global.php");

if( isset($_POST['MSG']) )
{
	if( !$loginStat )
	{
		$actionResult = "0";
	}
	else
	{
		$msg_con = filterCode($_POST['MSG'],$blog_config['url_short']);

		if( empty($msg_con) || getStrlen($msg_con) > 140 )
		{
			$actionResult = "-2";
		}
		else
		{
			$pic_url = (isset($_POST['PIC']) && file_exists($blog_config['pic_upload'].$_POST['PIC'])) ? $_POST['PIC'] : "";

			$DB = database();
			
			$updateRes = blogAction::blogUpdate($msg_con,$pic_url,"Web");

			$DB->close();

			if( $updateRes )
			{
				$actionResult = "1";
			}
			else
				$actionResult = "-1";
		}
	}
}

if( isset($_POST['deleteId'],$_POST['deletePic']) )
{
	if( $loginStat )
	{
		$DB = database(false);
		
		$delRes = blogAction::delBlog($_POST['deleteId'],$_POST['deletePic']);

		$DB->close();

		if( $delRes )
		{
			$actionResult = "1";
		}
	}
}

if( isset($actionResult) )
{
	echo $actionResult;
}
else
{
	$getPage = (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 1) ? intval($_GET['p']) : 1;

	$DB = database();

	$blogArr = BlogAction::getBlog($getPage,$blog_config['per_blog']);

	$blogDay = BlogAction::getStat((time()-86400));

	$blogWeek = BlogAction::getStat((time()-604800));

	$DB->close();

	$pageArr = pageAction::blogPage($blogArr['Total'],$blog_config['per_blog'],$getPage);

	$tmp = template("index.html");

	$tmp->assign( 'blogConfig', $blog_config );

	$tmp->assign( 'loginStat', $loginStat );

	$tmp->assign( 'thisPage', $getPage );

	$tmp->assign( 'blogArr', $blogArr );

	$tmp->assign( 'blogDay', $blogDay );

	$tmp->assign( 'blogWeek', $blogWeek );

	$tmp->assign( 'pageArr', $pageArr );

	$tmp->output();
}

ob_end_flush();
?>