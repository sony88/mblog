<?php
include_once "D:/wamp/www/mblog/class/templateExtensions/stripslashes.php";
include_once "D:/wamp/www/mblog/class/templateExtensions/datetime.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
 &rsaquo; 管理评论</title><link rel="stylesheet" type="text/css" href="_static/<?php
echo $_obj['blogConfig']['skin'];
?>
/style.css" /><script type="text/javascript" src="_static/js/jquery.js"></script><script type="text/javascript" src="_static/js/form.js"></script><script type="text/javascript" src="_static/js/action.js"></script></head><body><div id="navbar"><?php
if ($_obj['loginStat'] == "1"){
?><a href="./friend.php">关注管理</a><a href="./friend.php?t=fans">粉丝管理</a><a href="./comments.php">评论管理</a><a href="./setting.php">微博设置</a><a href="./password.php">账户密码</a><a href="./login.php?do=logout">安全退出</a><?php
} else {
?><a href="./login.php">登录</a><?php
}
?></div><div class="wrapper"><div id="header"><img src="<?php
echo $_obj['blogConfig']['avatar_upload'];
?>
avatar.jpg" alt="<?php
echo phpsay_stripslashes($_obj['blogConfig']['nickname']);
?>
" id="avatar" /><h1><a href="./"><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
</a><span><a href="./"><?php
echo $_obj['blogConfig']['siteurl'];
?>
</a></span></h1><ul id="menu"><li><a href="./">微博</a></li><li><a href="./follow.php">关注</a></li><li><a href="./follow.php?t=fans">粉丝</a></li></ul></div><div id="description"><span class="left"><?php
echo phpsay_stripslashes($_obj['blogConfig']['siteintro']);
?>
</span><span class="right"><a href="./rss.php" title="RSS订阅" target="_blank"></a></span></div><div id="main"><?php
if (!empty($_obj['commentArr']['Comment'])){
if (!is_array($_obj['commentArr']['Comment']))
$_obj['commentArr']['Comment']=array(array('Comment'=>$_obj['commentArr']['Comment']));
$_tmp_arr_keys=array_keys($_obj['commentArr']['Comment']);
if ($_tmp_arr_keys[0]!='0')
$_obj['commentArr']['Comment']=array(0=>$_obj['commentArr']['Comment']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['commentArr']['Comment'] as $rowcnt=>$Comment) {
$Comment['ROWCNT']=$rowcnt;
$Comment['ALTROW']=$rowcnt%2;
$Comment['ROWBIT']=$rowcnt%2;
$_obj=&$Comment;
?><div class="entry<?php
if ($_obj['ROWCNT'] == "0"){
?> first<?php
}
?>"><div class="content"><?php
echo $_obj['message'];
?>
</div><div class="from"><span class="mycome"><?php
echo phpsay_datetime($_obj['dateline']);
?>
 by <?php
if ($_obj['nickname'] == ""){
?>Anonymity<?php
} else {
?><?php
echo $_obj['nickname'];
?>
<?php
}
?></span><span class="option"><?php
if ($_obj['display'] == "0"){
?><span class="check" id="display_<?php
echo $_obj['cid'];
?>
" onclick="displayComment(<?php
echo $_obj['cid'];
?>
)">审核</span><?php
}
?><span class="delete" onclick="delComment(<?php
echo $_obj['mid'];
?>
,<?php
echo $_obj['cid'];
?>
,1)">删除</span></span></div><div class="clear"></div></div><?php
}
$_obj=$_stack[--$_stack_cnt];}
?><div class="pages"><em>共 <?php
echo $_obj['commentArr']['Total'];
?>
 篇</em><?php
echo $_obj['pageArr']['pageFirst'];
?>
<?php
echo $_obj['pageArr']['pagePre'];
?>
<?php
echo $_obj['pageArr']['pageList'];
?>
<?php
echo $_obj['pageArr']['pageNext'];
?>
<?php
echo $_obj['pageArr']['pageLast'];
?>
</div></div></div><div id="footer"><span>&copy; 2006～2011 PHPSay.com<?php
if (!empty($_obj['blogConfig']['miibeian'])){
?> ,<?php
echo $_obj['blogConfig']['miibeian'];
?>
<?php
}
?></span>Powered by <a href="http://www.phpsay.com/" target="_blank">PHPSay-Microblog</a> <?php
echo $_obj['blogConfig']['version'];
?>
 ,Theme By Hoofei<script>$.get('./friend_server.php?do=update');</script><?php
echo phpsay_stripslashes($_obj['blogConfig']['tracking_code']);
?>
</div></body></html>