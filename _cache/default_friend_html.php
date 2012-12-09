<?php
include_once "D:/wamp/www/mblog/class/templateExtensions/stripslashes.php";
include_once "D:/wamp/www/mblog/class/templateExtensions/datetime.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
 &rsaquo; 管理<?php
if ($_obj['friendType'] == "1"){
?>我关注的人<?php
} else {
?>关注我的人<?php
}
?></title><link rel="stylesheet" type="text/css" href="_static/<?php
echo $_obj['blogConfig']['skin'];
?>
/style.css" /><script type="text/javascript" src="_static/js/jquery.js"></script><script type="text/javascript" src="_static/js/form.js"></script><script type="text/javascript" src="_static/js/friend.js"></script></head><body><div id="navbar"><?php
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
</span><span class="right"><a href="./rss.php" title="RSS订阅" target="_blank"></a></span></div><div id="main" class="profile_edit"><h2 class="nav_title"><?php
if ($_obj['friendType'] == "1"){
?>我关注的人<?php
} else {
?>关注我的人<?php
}
?></h2><?php
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
?><div class="friend"><?php
if ($_stack[0]['friendType'] == "1"){
?><span><a href="javascript:;" onclick="delFriend('<?php
echo $_obj['furl'];
?>
','delFollow')">删除</a></span><?php
} else {
?><span><a href="javascript:;" onclick="delFriend('<?php
echo $_obj['furl'];
?>
','delFans')">删除</a></span><span><a href="javascript:;" onclick="addFriend('<?php
echo $_obj['furl'];
?>
')">关注</a></span><?php
}
?><span><?php
if ($_obj['fupdate'] > "0"){
?>上次成功请求于<?php
echo phpsay_datetime($_obj['fupdate']);
?>
<?php
} else {
?>尚未成功请求<?php
}
?></span><span><strong><?php
echo $_obj['friendname'];
?>
：</strong><a href="<?php
echo $_obj['furl'];
?>
" target="_blank"><?php
echo $_obj['furl'];
?>
</a></span></div><?php
}
$_obj=$_stack[--$_stack_cnt];}
?> <div class="pages"><em>共 <?php
echo $_obj['friendArr']['Total'];
?>
 位</em><?php
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
</div><form id="addForm" method="post"><h2 class="nav_title">添加关注</h2><div class="s_f"><strong>好友微博地址:</strong> （ 以 http:// 开始，以 / 结束。只可以关注 PHPSay-Microblog 系统用户 ）<input type="text" class="input_message" name="url" value="http://" maxlength="90" /></div><div class="s_s"><input type="submit" name="addFollow" id="submit" value="确认关注" class="input_submit" /></div><div id="result" class="s_r"></div></form><div class="clear"></div></div></div><div id="footer"><span>&copy; 2006～2011 PHPSay.com<?php
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