<?php
include_once "D:/wamp/www/mblog/class/templateExtensions/stripslashes.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
 &rsaquo; 设置</title><meta content="<?php
echo phpsay_stripslashes($_obj['blogConfig']['keywords']);
?>
" name="keywords" /><meta content="<?php
echo phpsay_stripslashes($_obj['blogConfig']['description']);
?>
" name="description" /><link rel="stylesheet" type="text/css" href="_static/<?php
echo $_obj['blogConfig']['skin'];
?>
/style.css" /><script type="text/javascript" src="_static/js/jquery.js"></script><script type="text/javascript" src="_static/js/form.js"></script><script type="text/javascript" src="_static/js/swfobject.js"></script><script type="text/javascript" src="_static/js/uploadify.js"></script><script type="text/javascript" src="_static/js/action.js"></script><script type="text/javascript">var skinName = "<?php
echo $_obj['blogConfig']['skin'];
?>
";</script><script type="text/javascript" src="_static/js/setting.js"></script></head><body><div id="navbar"><?php
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
</span><span class="right"><a href="./rss.php" title="RSS订阅" target="_blank"></a></span></div><div id="main" class="profile_edit"><h2 class="nav_title">微博设置（Ver <?php
echo $_obj['blogConfig']['version'];
?>
）</h2><div class="s_a"><div class="avatar_img"><img src="<?php
echo $_obj['blogConfig']['avatar_upload'];
?>
avatar.jpg" id="avatar_now"></div><div class="avatar_des">更换头像 (50×50):</div><div class="avatar_ipt"><input type="file" name="avatar" id="upavatar" class="input_file" /></div><div class="avatar_res" id="uploadifyQueue"></div></div><form name="setForm" id="setForm" method="post"><input type="hidden" name="version" value="<?php
echo $_obj['blogConfig']['version'];
?>
" /><div class="s_c"><span>切换皮肤:</span><select name="skin"><?php
if (!empty($_obj['skinArr'])){
if (!is_array($_obj['skinArr']))
$_obj['skinArr']=array(array('skinArr'=>$_obj['skinArr']));
$_tmp_arr_keys=array_keys($_obj['skinArr']);
if ($_tmp_arr_keys[0]!='0')
$_obj['skinArr']=array(0=>$_obj['skinArr']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['skinArr'] as $rowcnt=>$skinArr) {
$skinArr['ROWCNT']=$rowcnt;
$skinArr['ALTROW']=$rowcnt%2;
$skinArr['ROWBIT']=$rowcnt%2;
$_obj=&$skinArr;
?><option value="<?php
echo $_obj['skin'];
?>
"<?php
if ($_obj['skin'] == $_stack[0]['blogConfig']['skin']){
?> selected<?php
}
?>><?php
echo $_obj['skin'];
?>
</option><?php
}
$_obj=$_stack[--$_stack_cnt];}
?></select></div><div class="s_d"><span>URL缩短:</span><select name="url_short"><option value="false">关闭</option><option value="true"<?php
if (!empty($_obj['blogConfig']['url_short'])){
?> selected<?php
}
?>>开启</option></select></div><div class="s_d"><span>每页微博:</span><select name="per_blog" id="per_blog"><option value="5"<?php
if ($_obj['blogConfig']['per_blog'] == "5"){
?> selected<?php
}
?>>5</option><option value="10"<?php
if ($_obj['blogConfig']['per_blog'] == "10"){
?> selected<?php
}
?>>10</option><option value="15"<?php
if ($_obj['blogConfig']['per_blog'] == "15"){
?> selected<?php
}
?>>15</option><option value="20"<?php
if ($_obj['blogConfig']['per_blog'] == "20"){
?> selected<?php
}
?>>20</option><option value="25"<?php
if ($_obj['blogConfig']['per_blog'] == "25"){
?> selected<?php
}
?>>25</option><option value="30"<?php
if ($_obj['blogConfig']['per_blog'] == "30"){
?> selected<?php
}
?>>30</option><option value="35"<?php
if ($_obj['blogConfig']['per_blog'] == "35"){
?> selected<?php
}
?>>35</option><option value="40"<?php
if ($_obj['blogConfig']['per_blog'] == "40"){
?> selected<?php
}
?>>40</option><option value="45"<?php
if ($_obj['blogConfig']['per_blog'] == "45"){
?> selected<?php
}
?>>45</option><option value="50"<?php
if ($_obj['blogConfig']['per_blog'] == "50"){
?> selected<?php
}
?>>50</option></select></div><div class="s_d"><span>评论状态:</span><select name="comment_open"><option value="0">关闭</option><option value="1"<?php
if ($_obj['blogConfig']['comment_open'] == "1"){
?> selected<?php
}
?>>开放</option><option value="2"<?php
if ($_obj['blogConfig']['comment_open'] == "2"){
?> selected<?php
}
?>>审核</option></select></div><div class="s_d"><span>每页评论:</span><select name="per_comment" id="per_comment"><option value="5"<?php
if ($_obj['blogConfig']['per_comment'] == "5"){
?> selected<?php
}
?>>5</option><option value="10"<?php
if ($_obj['blogConfig']['per_comment'] == "10"){
?> selected<?php
}
?>>10</option><option value="20"<?php
if ($_obj['blogConfig']['per_comment'] == "20"){
?> selected<?php
}
?>>20</option><option value="30"<?php
if ($_obj['blogConfig']['per_comment'] == "30"){
?> selected<?php
}
?>>30</option><option value="40"<?php
if ($_obj['blogConfig']['per_comment'] == "40"){
?> selected<?php
}
?>>40</option><option value="50"<?php
if ($_obj['blogConfig']['per_comment'] == "50"){
?> selected<?php
}
?>>50</option><option value="60"<?php
if ($_obj['blogConfig']['per_comment'] == "60"){
?> selected<?php
}
?>>60</option></select></div><div class="s_e"><strong>您的昵称:</strong><input type="text" class="input_narrow" name="nickname" value="<?php
echo phpsay_stripslashes($_obj['blogConfig']['nickname']);
?>
" maxlength="10" /></div><div class="s_e"><strong>时区设置:</strong> （ <a href="http://php.net/manual/en/timezones.php" target="_blank">查找时区</a>，中国用户无需更改 ）<input type="text" class="input_narrow" name="timezone" value="<?php
echo $_obj['blogConfig']['timezone'];
?>
" maxlength="20" /></div><div class="s_e"><strong>微博标题:</strong><input type="text" class="input_narrow" name="sitename" value="<?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
" maxlength="20" /></div><div class="s_e"><strong>微博地址:</strong> （ 以 http:// 开头，结尾不要加 / ）<input type="text" class="input_narrow" name="siteurl" value="<?php
echo phpsay_stripslashes($_obj['blogConfig']['siteurl']);
?>
" maxlength="60" /></div><div class="s_b"><strong>微博说明:</strong> （ 支持HTML ）<textarea name="siteintro" class="input_textarea" onkeydown="if(event.keyCode==13){return false;}"><?php
echo phpsay_stripslashes($_obj['blogConfig']['siteintro']);
?>
</textarea></div><div class="s_e"><strong>Meta 关键字:</strong> （ 多个以 , 隔开 ）<input type="text" class="input_narrow" name="keywords" value="<?php
echo phpsay_stripslashes($_obj['blogConfig']['keywords']);
?>
" maxlength="100"/></div><div class="s_e"><strong>备案信息:</strong><input type="text" class="input_narrow" name="miibeian" value="<?php
echo $_obj['blogConfig']['miibeian'];
?>
" maxlength="15"/></div><div class="s_b"><strong>Meta 描述:</strong> （ 建议小于等于 200 个字符 ）<textarea name="description" class="input_textarea" onkeydown="if(event.keyCode==13){return false;}"><?php
echo phpsay_stripslashes($_obj['blogConfig']['description']);
?>
</textarea></div><div class="s_b"><strong>统计代码:</strong> （ 放置统计代码，加载于页面尾部 ）<textarea name="tracking_code" class="input_textarea"><?php
echo $_obj['trackingCode'];
?>
</textarea></div><div class="s_e"><strong>上传图片保存位置:</strong> （ 不建议更改 ）<input type="text" class="input_narrow" name="pic_upload" value="<?php
echo $_obj['blogConfig']['pic_upload'];
?>
" maxlength="30" readonly /></div><div class="s_e"><strong>头像保存位置:</strong> （ 不建议更改 ）<input type="text" class="input_narrow" name="avatar_upload" value="<?php
echo $_obj['blogConfig']['avatar_upload'];
?>
" maxlength="30" readonly /></div><div class="s_s"><input type="submit" name="save" value="保存设置" class="input_submit" /></div><div id="result" class="s_r"></div></form><div class="clear"></div></div></div><div id="footer"><span>&copy; 2006～2011 PHPSay.com<?php
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