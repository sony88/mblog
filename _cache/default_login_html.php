<?php
include_once "D:/wamp/www/mblog/class/templateExtensions/stripslashes.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="zh-CN"><head><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
 &rsaquo; 登录</title><link rel="stylesheet" type="text/css" href="_static/<?php
echo $_obj['blogConfig']['skin'];
?>
/login.css" /><meta name='robots' content='noindex,nofollow' /><?php
if ($_obj['loginResult'] == "ok"){
?><meta http-equiv="Page-Exit" content="blendTrans(Duration=1.0)"><meta http-equiv="refresh" content="1; url=./"><?php
}
?></head><body><p id="backtoblog"><a href="./">&larr; 返回微博首页</a></p><div id="login"><h1><a href="http://www.phpsay.com" title="Powered by PHPSay-Microblog" target="_blank"><?php
echo phpsay_stripslashes($_obj['blogConfig']['sitename']);
?>
</a></h1><?php
if ($_obj['loginResult'] == "error"){
?><div id="login_result"><strong>错误</strong>：用户名与密码不匹配。</div><?php
}
?><?php
if ($_obj['loginResult'] == "ok"){
?><div id="login_result">^_^ 登录成功 正在返回...</div><?php
}
?><form name="loginform" id="loginform" action="./login.php" method="post"><p><label>用户名<br /><input type="text" name="username" id="username" class="input" value="" size="20" tabindex="10" /></label></p><p><label>密码<br /><input type="password" name="password" id="password" class="input" value="" size="20" tabindex="20" /></label></p><p class="submit"><input type="submit" name="submit" id="submit" value="登录" /></p></form><script>document.getElementById('username').focus();</script></div></body></html>