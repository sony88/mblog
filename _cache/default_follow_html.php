<?php
include_once "D:/wamp/www/mblog/class/templateExtensions/stripslashes.php";
include_once "D:/wamp/www/mblog/class/templateExtensions/datetime.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
 &rsaquo; <?php
if ($_obj['friendType'] == "1"){
?>我关注的人<?php
} else {
?>关注我的人<?php
}
?></title><meta content="<?php
echo phpsay_stripslashes($_obj['blogConfig']['keywords']);
?>
" name="keywords" /><meta content="<?php
echo phpsay_stripslashes($_obj['blogConfig']['description']);
?>
" name="description" /><link rel="stylesheet" type="text/css" href="_static/<?php
echo $_obj['blogConfig']['skin'];
?>
/style.css" /><script type="text/javascript" src="_static/js/jquery.js"></script><script type="text/javascript" src="_static/js/form.js"></script><script type="text/javascript" src="_static/js/action.js"></script><script type="text/javascript" src="_static/js/zoom.js"></script></head><body><div id="navbar"><?php
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
if (!empty($_obj['friendArr']['Friend'])){
if (!is_array($_obj['friendArr']['Friend']))
$_obj['friendArr']['Friend']=array(array('Friend'=>$_obj['friendArr']['Friend']));
$_tmp_arr_keys=array_keys($_obj['friendArr']['Friend']);
if ($_tmp_arr_keys[0]!='0')
$_obj['friendArr']['Friend']=array(0=>$_obj['friendArr']['Friend']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['friendArr']['Friend'] as $rowcnt=>$Friend) {
$Friend['ROWCNT']=$rowcnt;
$Friend['ALTROW']=$rowcnt%2;
$Friend['ROWBIT']=$rowcnt%2;
$_obj=&$Friend;
?><div class="entry<?php
if ($_obj['ROWCNT'] == "0"){
?> first<?php
}
?>"><div class="avatar"><a href="<?php
echo $_obj['furl'];
?>
" target="_blank"><img src="./friend_server.php?avatar=<?php
echo $_obj['friendavatar'];
?>
" alt="<?php
echo $_obj['friendname'];
?>
" title="<?php
echo $_obj['friendname'];
?>
"></a></div><div class="info"><div class="content"><a href="<?php
echo $_obj['furl'];
?>
" target="_blank"><?php
echo $_obj['friendname'];
?>
</a>：<?php
echo $_obj['friendmsg'];
?>
<?php
if ($_obj['friendpics'] != ""){
?><a id="fpic_<?php
echo $_obj['fid'];
?>
" href="<?php
echo $_obj['friendpicb'];
?>
" class="zoom"><img src="<?php
echo $_obj['friendpics'];
?>
" onError="this.src='./friend_server.php?img=<?php
echo $_obj['friendpids'];
?>
';$('#fpic_<?php
echo $_obj['fid'];
?>
').attr('href','./friend_server.php?img=<?php
echo $_obj['friendpidb'];
?>
')"></a><?php
}
?></div><div class="other"><span><?php
echo phpsay_datetime($_obj['friendtime']);
?>
 from <?php
echo $_obj['friendorigin'];
?>
<?php
if ($_stack[0]['loginStat'] == "1"){
?>&nbsp;&nbsp;<a href="javascript:;" onclick="fComLoad(<?php
echo $_obj['fid'];
?>
)">(Reply)</a><?php
}
?></span></div></div><div class="clear"></div></div><?php
if ($_stack[0]['loginStat'] == "1"){
?><div class="comment" id="comment_<?php
echo $_obj['fid'];
?>
"><form id="re_form_<?php
echo $_obj['fid'];
?>
"><input type="text" name="comment" id="re_msg_<?php
echo $_obj['fid'];
?>
" class="long" maxlength="70" onkeydown="if(event.keyCode==13){fComDo(<?php
echo $_obj['fid'];
?>
)}" /><input type="hidden" name="mid" value="<?php
echo $_obj['friendmid'];
?>
" /><input type="hidden" name="furl" value="<?php
echo $_obj['furl'];
?>
" /><input type="submit" class="submit" id="re_sub_<?php
echo $_obj['fid'];
?>
" value="" onclick="fComDo(<?php
echo $_obj['fid'];
?>
)" /></form></div><?php
}
?><?php
}
$_obj=$_stack[--$_stack_cnt];}
?><div class="pages"><em><?php
if ($_obj['friendType'] == "1"){
?>共关注了 <?php
echo $_obj['friendArr']['Total'];
?>
 位好友<?php
} else {
?>共被 <?php
echo $_obj['friendArr']['Total'];
?>
 位好友关注<?php
}
?></em><?php
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