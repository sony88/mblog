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
			else
			{
				$('#result').html(msg);
			}

			$('#result').show();
		}
	);
});