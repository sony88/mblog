$(document).ready(function()
{
	$('#upavatar').uploadify({
		'expressInstall':'_static/js/expressInstall.swf',
		'uploader':'_static/js/uploadify.swf',
		'script':'upload.php',
		'scriptData':{'secure':parseCookie('adminSecure')},
		'cancelImg':'_static/'+skinName+'/cancel.png',
		'auto':true,
		'fileDataName':'avatar',
		'buttonImg':'_static/'+skinName+'/upavatar.png',
		'width':65,
		'height':27,
		'queueID':'uploadifyQueue',
		'fileDesc':'图片（*.jpg;*.jpeg;*.gif;*.png）',
		'fileExt':'*.jpg;*.jpeg;*.gif;*.png',
		'sizeLimit': 1048576,
		'onSelect':function(){$("#uploadifyQueue").html("")},
		'onComplete':upComplete
	});

	function upComplete(event,queueID,fileObj,response,data)
	{
		if( response == "0" )
		{
			$("#uploadifyQueue").html("<div class='uploadifyQueueItem uploadifyError'>异常退出，请重新登录……</div>");

			setTimeout("location.href='./login.php';", 1500);
		}
		else if( response == "1" )
		{
			var avatar = $("#avatar_now").attr('src') + "?"+Math.random();

			$("#avatar_now").attr('src', avatar);

			$("#avatar").attr('src', avatar);
		}
		else
		{
			$("#uploadifyQueue").html("<div class='uploadifyQueueItem uploadifyError'>"+response+"</div>");
		}
	}

	$('#setForm').ajaxForm
	(
		function(msg)
		{
			if( msg == "0" )
			{
				$('#result').html('异常退出，请重新登录……');

				setTimeout("location.href='./login.php';", 1500);
			}
			else if( msg == "1" )
			{
				$('#result').html('设置已保存');
			}
			else
			{
				$('#result').html(msg);
			}

			$('#result').show();
		}
	);
});