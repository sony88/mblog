<?php
require_once(dirname(__FILE__)."/global.php");

$getPage = (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 1) ? intval($_GET['p']) : 1;

$friendType = (isset($_GET['t']) && $_GET['t'] == "fans") ? 2 : 1;

$DB = database();

$friendArr = BlogAction::getFriend("`ftype`=".$friendType." AND `fupdate` > ".(time()-900),"`friendtime`",$getPage,20);

$DB->close();

unset($DB);

$pageArr = pageAction::blogPage($friendArr['Total'],20,$getPage);

$tmp = template("follow.html");

$tmp->assign( 'blogConfig',  $blog_config );

$tmp->assign( 'loginStat',  $loginStat );

$tmp->assign( 'friendType',  $friendType );

$tmp->assign( 'friendArr',  $friendArr );

$tmp->assign( 'pageArr', $pageArr );

$tmp->output();

unset($tmp);

ob_end_flush();
?>