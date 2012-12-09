$(document).ready(function()
{
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
				$('input[name=save]').attr('disabled', true);

				$('#result').html('修改成功，请重新登录……');

				setTimeout("location.href='./login.php';", 1500);
			}
			else
			{
				$('#result').html(msg);
			}

			$('#result').show();
		}
	);
});