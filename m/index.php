<?php
require_once(dirname(__FILE__)."/global.php");

if( isset($_GET['do']) && $_GET['do'] == "logout" )
{
	loginOut();

	header("location:./?login");
}
else
{
	if( !$loginStat )
	{
		$loginResult = "";

		if( isset($_POST['username'],$_POST['password']) )
		{
			$username = filterCode($_POST['username']);

			$password = filterCode($_POST['password']);

			if( loginCheck($username,$password) )
			{
				header("location:./");

				exit;
			}
			else
				$loginResult = "用户名与密码不匹配！";
		}

		echo '<?xml version="1.0" encoding="UTF-8"?>';

		$tmp = template("login.html",false);

		$tmp->assign( 'blogConfig', $blog_config );

		$tmp->assign( 'loginResult',  $loginResult );

		$tmp->assign( 'nowTime', date('H:i') );

		$tmp->output();
	}
	else
	{
		$postResult = "";

		$DB = database();

		if( isset($_GET['delId'],$_GET['delPic']) && is_numeric($_GET['delId']) && $_GET['delId'] > 0 )
		{
			if( blogAction::delBlog($_GET['delId'],$_GET['delPic']) )
			{
				$postResult = "删除成功";
			}
		}

		if( isset($_POST['MSG']) )
		{
			$msg_con = filterCode($_POST['MSG'],$blog_config['url_short']);

			if( empty($msg_con) || getStrlen($msg_con) > 140 )
			{
				$postResult = "内容不能为空且不超过140个字符";
			}
			else
			{
				if( blogAction::blogUpdate($msg_con,"","Wap") )
				{
					$postResult = "发表成功";
				}
				else
				{
					$postResult = "发表失败";
				}
			}
		}

		$getPage = (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 1) ? intval($_GET['p']) : 1;

		$blogArr = BlogAction::getBlog($getPage,10);

		$DB->close();

		$pageArr = pageAction::mobilePage($blogArr['Total'],10,$getPage);

		echo '<?xml version="1.0" encoding="UTF-8"?>';

		$tmp = template("index.html",false);

		$tmp->assign( 'blogConfig', $blog_config );

		$tmp->assign( 'thisPage', $getPage );

		$tmp->assign( 'postResult',  $postResult );

		$tmp->assign( 'blogArr', $blogArr );

		$tmp->assign( 'pageArr', $pageArr );

		$tmp->assign( 'nowTime', date('H:i') );

		$tmp->output();
	}
}
?>