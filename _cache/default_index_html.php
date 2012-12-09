<?php
include_once "D:/wamp/www/mblog/class/templateExtensions/stripslashes.php";
include_once "D:/wamp/www/mblog/class/templateExtensions/datetime.php";
include_once "D:/wamp/www/mblog/class/templateExtensions/origin.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
</title><meta content="<?php
echo phpsay_stripslashes($_obj['blogConfig']['keywords']);
?>
" name="keywords" /><meta content="<?php
echo phpsay_stripslashes($_obj['blogConfig']['description']);
?>
" name="description" /><link rel="alternate" type="application/rss+xml" href="rss.php" title="<?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
" /><link rel="stylesheet" type="text/css" href="_static/<?php
echo $_obj['blogConfig']['skin'];
?>
/style.css" /><script type="text/javascript" src="_static/js/jquery.js"></script><script type="text/javascript" src="_static/js/form.js"></script><script type="text/javascript" src="_static/js/action.js"></script><script type="text/javascript" src="_static/js/zoom.js"></script><?php
if ($_obj['loginStat'] == "1"){
?><script type="text/javascript" src="_static/js/swfobject.js"></script><script type="text/javascript" src="_static/js/uploadify.js"></script><script type="text/javascript">var skinName = "<?php
echo $_obj['blogConfig']['skin'];
?>
";</script><script type="text/javascript" src="_static/js/index.js"></script><?php
}
?></head><body><div id="navbar"><?php
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
if ($_obj['loginStat'] == "1"){
?><div class="entry_post"><form id="submit_form"><h2 id="input_count">140</h2><textarea name="MSG" id="input_message" rows="4" onkeydown="if(event.keyCode==13){return false;}" onkeyup="cState()"></textarea><input type="hidden" name="PIC" id="input_picture" value=""><input type="submit" value="" id="update" class="btn_update btn_update_close" onclick="updateDo()"></form><span class="insert"><input type="file" name="picture" id="picture" class="input_file"></span><span id="upresult" class="insert"></span><div class="clear"></div></div><?php
}
?><?php
if (!empty($_obj['blogArr']['Blog'])){
if (!is_array($_obj['blogArr']['Blog']))
$_obj['blogArr']['Blog']=array(array('Blog'=>$_obj['blogArr']['Blog']));
$_tmp_arr_keys=array_keys($_obj['blogArr']['Blog']);
if ($_tmp_arr_keys[0]!='0')
$_obj['blogArr']['Blog']=array(0=>$_obj['blogArr']['Blog']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['blogArr']['Blog'] as $rowcnt=>$Blog) {
$Blog['ROWCNT']=$rowcnt;
$Blog['ALTROW']=$rowcnt%2;
$Blog['ROWBIT']=$rowcnt%2;
$_obj=&$Blog;
?><div class="entry<?php
if ($_obj['ROWCNT'] == "0"){
?> first<?php
}
?>"><div class="content"><?php
echo $_obj['message'];
?>
<?php
if ($_obj['picture'] != ""){
?><a href="<?php
echo $_stack[0]['blogConfig']['pic_upload'];
?>
<?php
echo $_obj['piclink'];
?>
" class="zoom"><img src="<?php
echo $_stack[0]['blogConfig']['pic_upload'];
?>
<?php
echo $_obj['picture'];
?>
"></a><?php
}
?></div><div class="from"><span class="mycome"><?php
echo phpsay_datetime($_obj['dateline']);
?>
 from <?php
echo phpsay_origin($_obj['origin']);
?>
</span><span class="option"><span class="reply" onclick="loadComments(<?php
echo $_obj['mid'];
?>
)">评论<span id="reply_<?php
echo $_obj['mid'];
?>
">(<?php
echo $_obj['comments'];
?>
)</span></span><?php
if ($_stack[0]['loginStat'] == "1"){
?><span class="delete" onclick="deleteBlog(<?php
echo $_obj['mid'];
?>
,'<?php
echo $_obj['picture'];
?>
')">删除</span><?php
}
?></span></div><div class="clear"></div> </div><div class="comment" id="comment_<?php
echo $_obj['mid'];
?>
"></div><?php
}
$_obj=$_stack[--$_stack_cnt];}
?> <div class="pages"><em>共 <?php
echo $_obj['blogArr']['Total'];
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
<?php
if ($_obj['pageArr']['pageTotal'] > "5"){
?><kbd><input type="text" name="page" size="3" onkeydown="if(event.keyCode == 13){cPage(<?php
echo $_obj['pageArr']['pageTotal'];
?>
)}" title="快速翻页" /></kbd><?php
}
?></div> <div id="stat">数据统计：24小时内发布<strong><?php
echo $_obj['blogDay'];
?>
</strong>篇 ... 一周内发布<strong><?php
echo $_obj['blogWeek'];
?>
</strong>篇 ... 总发布数<strong><?php
echo $_obj['blogArr']['Total'];
?>
</strong></div></div></div><div id="footer"><span>&copy; 2006～2011 PHPSay.com<?php
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