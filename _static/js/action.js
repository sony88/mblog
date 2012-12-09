function parseCookie(varName)
{
	var srcCookie = window.document.cookie;

	if(srcCookie=="")
	{
		return "";
	}

	var nPos=srcCookie.lastIndexOf(varName+"=");
	
	if(nPos>0)
	{
		if(nPos>=2)
		{
			nPos=srcCookie.indexOf("; "+varName+"=",nPos-2);
		}
		else
		{
			nPos=srcCookie.indexOf("; "+varName+"=");
		}
	}

	if(nPos>=0)
	{
		nPos=srcCookie.indexOf('=',nPos)+1;

		var nTailPos=srcCookie.indexOf("; ",nPos);
		
		if(nTailPos>0)
		{
			return srcCookie.substring(nPos,nTailPos);
		}
		else
		{
			return srcCookie.substr(nPos);
		}
	}

	return "";
}

function cPage(num)
{
	var p = $.trim($('input[name=page]').val());

	if( p > num || p < 1)
	{
		alert('请输入 1 至 ' + num + ' 范围内的页码.');

		$('input[name=page]').val('');
	}
	else
	{
		window.location='./?p=' + p;
	}
}

function cState()
{
	var len = $.trim($("#input_message").val()).length;

	if( len < 1 || len > 140 )
	{
		$('#update').addClass('btn_update_close');
	}
	else
	{
		$('#update').removeClass('btn_update_close');
	}

	len = 140 - len;

	if( len < 0 )
	{
		$("#input_count").css("color","red");
	}
	else
	{
		$("#input_count").css("color","#333333");
	}

	$("#input_count").html(len);
}

function upadteCheck()
{
	var len = $.trim($("#input_message").val()).length;

	if( len < 1 || len > 140 )
	{
		return false;
	}

	$('#update').addClass('btn_update_close');
}

function updateBack(m)
{
	if( m == "1" )
	{
		$("#input_message").val('');
		
		$("#input_picture").val('');
		
		location.href = './';
	}
	else if( m == "0" )
	{
		location.href = './login.php';
	}
	else
	{
		if( m == "-1" )
		{
			alert("发布失败");
		}
		else if( m == "-2" )
		{
			alert("内容不能为空且不超过140个字符");
		}
		else
			alert("系统异常");

		$('#update').removeClass('btn_update_close');
	}
}

function updateDo()
{
	var options = {beforeSubmit:upadteCheck,success:updateBack,url:'./index.php',type:'post'};

	$('#submit_form').ajaxForm(options);
	
	return false;
}

function updateReplyNum(id,num)
{
	var obj = $("#reply_"+id);

	var o = parseInt(obj.html().replace(/\D/g,"")) + num;

	if( o < 0 )
	{
		o = 0;
	}
	
	obj.html("("+o+")");
}

function deleteBlog(id,pic)
{
	var truthBeTold = window.confirm("确定要删除此记录？");

	if( !truthBeTold )
	{
		return false;
	}

	$.post('./index.php', {deleteId:id,deletePic:pic},function(m){if(m == "1"){location.href=location.href}else{alert("删除失败")}});
}

function delComment(mid,cid,s)
{
	var truthBeTold = window.confirm("确定要删除此评论？");

	if( !truthBeTold )
	{
		return false;
	}

	$.post('./comment.php', {messageId:mid,deleteId:cid},function(m){if(m == "1"){if(s){location.href=location.href;}else{updateReplyNum(mid,-1);$("#comment_list_"+cid).remove();}}else{alert("删除失败")}});
}

function displayComment(cid)
{
	$.post('./comment.php', {displayId:cid},function(m){if(m == "1"){$("#display_"+cid).remove();}else{alert("审核操作出现异常")}});
}

function loadComments(id)
{
	var obj = $("#comment_"+id);

	if( obj.css('display') == "none" )
	{
		obj.html("<div class='load'><img src=./_static/images/loading.gif></div>");

		obj.slideDown("fast",function(){$.post('./comment.php', {id:id,pg:1},function(m){obj.html(m);});});
	}
	else
	{
		obj.slideUp("fast",function(){obj.html("");});
	}
}

function getComments(i,p,l)
{
	if(l)
	{
		$("#comment_"+i).html("<div class='load'><img src=./_static/images/loading.gif></div>");
	}

	$.post('./comment.php', {id:i,pg:p},function(m){$("#comment_"+i).html(m);});
}

function commentDo(id)
{
	var len = $.trim($("#input_message_"+id).val()).length;

	if( len >= 1 && len <= 70 )
	{
		var obj = $("#comment_div_"+id);

		var form = obj.html();

		var load = '<img src="./_static/images/loading.gif" align="absmiddle">';

		var options = {beforeSubmit:function(){obj.html(load)},success:function(m){
																					var b=parseInt(m);
																					var a=m.substr(m.indexOf(' ')+1);
																					if(b==1){
																						updateReplyNum(id,1);getComments(id,a,0)
																					}
																					else if(b==2){
																						updateReplyNum(id,1);
																						obj.html("<font class=red>"+a+"</font>");
																					}
																					else{
																						obj.html(form);
																						$("#input_submit_"+id).val('');
																						alert(a);
																						if(b==0){
																							$("#input_message_"+id).focus();
																						}
																						else if(b==-1){
																							try{$("#input_nickname_"+id).focus();}catch(e){}
																						}
																					}
																				},url:'./comment.php',type:'post'};
	}
	else
	{
		var options = {beforeSubmit:function(){return false}};
	}

	$('#comment_form_'+id).ajaxForm(options);
	
	return false;
}

function uploadSelect(event,queueID,fileObj)
{
	$("#update").attr('disabled', true);

	$('#update').addClass('btn_update_close');

	uploadCancel();
}

function uploadComplete(event,queueID,fileObj,response,data)
{
	$("#update").attr('disabled', false);

	$('#update').removeClass('btn_update_close');

	var resId = parseInt(response);

	var resMsg = response.substr(response.indexOf(' ')+1);

	if( resId == "0" )
	{
		if( resMsg == "login" )
		{
			$("#upresult").html("<div class='uploadresult'>异常退出，请重新登录……</div>");

			setTimeout("location.href='./login.php';", 1500);
		}
		else
		{
			$("#upresult").html("<div class='uploadresult'>"+resMsg+"</div>");
		}
	}
	else if( resId == "1" )
	{
		$("#input_picture").val(resMsg);

		$("#upresult").html("<div class='uploadresult'>上传成功！<a href='javascript:;' onclick='uploadCancel();'>删除？</a></div>");

		if( $("#input_message").val() == "" )
		{
			$("#input_message").val("分享图片");

			cState();
		}
	}
	else
		$("#upresult").html("<div class='uploadresult'>上传出现异常</div>");
}

function uploadCancel()
{
	$("#upresult").html("");

	if( $("#input_message").val() == "分享图片" )
	{
		$("#input_message").val("");

		cState();
	}

	var Pic = $("#input_picture").val();

	if( Pic != "" )
	{
		$.post('./upload.php', {deletePic:Pic});

		$("#input_picture").val("");
	}
}

function fComLoad(id)
{
	var obj = $("#comment_"+id);

	if( obj.css('display') == "none" )
	{
		obj.slideDown("fast");
	}
	else
	{
		obj.slideUp("fast");
	}
}

function fComDo(id)
{
	var len = $.trim($("#re_msg_"+id).val()).length;

	if( len >= 1 && len <= 70 )
	{
		var obj = $("#comment_"+id);

		var form = obj.html();

		var load = '<img src="./_static/images/loading.gif" align="absmiddle">';

		var options = {beforeSubmit:function(){obj.html(load)},success:function(m){
																					var b=parseInt(m);
																					var a=m.substr(m.indexOf(' ')+1);
																					if(b==1){
																						obj.html("<font class=red>评论成功 ^_^</font>");
																					}
																					else if(b==2){
																						obj.html("<font class=red>"+a+"</font>");
																					}
																					else{
																						obj.html(form);
																						$("#re_sub_"+id).val('');
																						alert(a);
																						$("#re_msg_"+id).focus();
																					}
																				},url:'./comment.php',type:'post'};
	}
	else
	{
		var options = {beforeSubmit:function(){return false}};
	}

	$('#re_form_'+id).ajaxForm(options);
	
	return false;
}