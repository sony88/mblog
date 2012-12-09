function delFriend(url,action)
{
	var truthBeTold = window.confirm("确定要删除？");

	if( !truthBeTold )
	{
		return false;
	}

	$.post('./friend_server.php?do='+action, { url:url },function(m){ if(m == "1"){ location.href=location.href }else{ alert("删除失败") } });
}

function addFriend(url)
{
	$('input[name=url]').val(url);

	$('input[name=addFollow]').focus();
}

function addFollowCheck()
{
	$('#result').show();

	var url = $.trim($('input[name=url]').val());

	if( url.length < 12 || url.substr(0,7) != "http://" || url.substr(url.length-1) != "/" )
	{
		$('#result').html("微博地址不合法");

		return false;
	}

	$("#submit").attr('disabled', true);

	$('#result').html("正在验证，请稍候……");
}

function addFollowBack(msg)
{
	msg = parseInt(msg,10);

	var errorMsg = [
					'异常退出，请重新登录……',
					'添加成功',
					'URL请以 http:// 开始，以 / 结束',
					'您不能将自已添加为好友',
					'该好友已关注，无需再次关注。',
					'通信失败，请稍后再试',
					'请确认您自已的微博昵称、地址均符合要求。',
					'您的好友无法与您建立通信',
					'该好友的微博异常，请稍候再试。'
					];
	
	$('#result').html(errorMsg[msg]);

	if( msg == 0 )
	{
		setTimeout("location.href='./login.php';", 1500);
	}
	else if( msg == 1 )
	{
		setTimeout("location.href='./friend.php';", 1500);
	}
	else
	{
		$("#submit").attr('disabled', false);
	}
}

$(document).ready(function()
{
	var options = {beforeSubmit:addFollowCheck,success:addFollowBack,url:'./friend_server.php?do=addFollow',type:'post'};

	$('#addForm').ajaxForm(options);
});