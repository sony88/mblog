<?php
require_once(dirname(__FILE__)."/global.php");

if( !$loginStat )
{
	header("location:./login.php");
}
else
{
	$getPage = (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 1) ? intval($_GET['p']) : 1;

	$DB = database();

	$commentArr = blogAction::getComment(0,$getPage,$blog_config['per_comment']);

	$DB->close();

	$pageArr = pageAction::blogPage($commentArr['Total'],$blog_config['per_comment'],$getPage);
	
	$tmp = template("comments.html");
	
	$tmp->assign( 'blogConfig', $blog_config );

	$tmp->assign( 'thisPage', $getPage );

	$tmp->assign( 'commentArr', $commentArr );

	$tmp->assign( 'pageArr', $pageArr );

	$tmp->assign( 'loginStat', $loginStat );
	
	$tmp->output();
}

ob_end_flush();
?>