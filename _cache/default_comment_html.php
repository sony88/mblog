<?php
include_once "D:/wamp/www/mblog/class/templateExtensions/datetime.php";

?><ul><?php
if ($_obj['commentArr']['Total'] > "0"){
?><?php
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
?><li id="comment_list_<?php
echo $_obj['cid'];
?>
"><p><?php
if ($_obj['display'] == "0"){
?><?php
if ($_stack[0]['loginStat'] == "1"){
?><?php
echo $_obj['message'];
?>
<?php
} else {
?><u>该评论审核中</u><?php
}
?><?php
} else {
?><?php
echo $_obj['message'];
?>
<?php
}
?></p><span class="from"><?php
echo phpsay_datetime($_obj['dateline']);
?>
<?php
if ($_obj['blogmaster'] == "1"){
?> <i>by</i> <u class="red"><?php
echo $_stack[0]['blogConfig']['nickname'];
?>
</u><?php
} else {
?><?php
if ($_obj['nickname'] == ""){
?> Anonymity<?php
} else {
?> <i>by</i> <?php
if ($_obj['blogurl'] == ""){
?><u><?php
echo $_obj['nickname'];
?>
</u><?php
} else {
?><a href="<?php
echo $_obj['blogurl'];
?>
" target="_blank"><?php
echo $_obj['nickname'];
?>
</a><?php
}
?><?php
}
?><?php
}
?></span><?php
if ($_stack[0]['loginStat'] == "1"){
?><span class="del"><a href="javascript:void(0);" onclick="delComment(<?php
echo $_obj['mid'];
?>
,<?php
echo $_obj['cid'];
?>
,0)">×</a></span><?php
if ($_obj['display'] == "0"){
?><span class="display" id="display_<?php
echo $_obj['cid'];
?>
"><a href="javascript:void(0);" onclick="displayComment(<?php
echo $_obj['cid'];
?>
)">○</a></span><?php
}
?><?php
}
?></li><?php
}
$_obj=$_stack[--$_stack_cnt];}
?><?php
if ($_obj['pageArr']['pageTotal'] > "1"){
?><li class="page"><?php
echo $_obj['pageArr']['pagePre'];
?>
<?php
echo $_obj['pageArr']['pageNext'];
?>
</li><?php
}
?><?php
} else {
?><li><i>暂无评论</i></li><?php
}
?></ul><?php
if ($_obj['blogConfig']['comment_open'] >= "1"){
?><div id="comment_div_<?php
echo $_obj['messageId'];
?>
"><form id="comment_form_<?php
echo $_obj['messageId'];
?>
"><?php
if ($_obj['loginStat'] == "0"){
?><input type="text" name="nickname" id="input_nickname_<?php
echo $_obj['messageId'];
?>
" class="short" maxlength="10" onkeydown="if(event.keyCode==13){return false;}" />:<?php
}
?><input type="text" name="comment" id="input_message_<?php
echo $_obj['messageId'];
?>
" class="long" maxlength="70" onkeydown="if(event.keyCode==13){commentDo(<?php
echo $_obj['messageId'];
?>
)}" /><input type="hidden" name="mid" value="<?php
echo $_obj['messageId'];
?>
" /><input type="submit" class="submit" id="input_submit_<?php
echo $_obj['messageId'];
?>
" value="" onclick="commentDo(<?php
echo $_obj['messageId'];
?>
)" /></form></div><?php
}
?>