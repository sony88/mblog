$(document).ready(function()
{
	$("#input_message").val('');

	$("#input_picture").val('');
	
	$('#picture').uploadify({
		'expressInstall':'_static/js/expressInstall.swf',
		'uploader':'_static/js/uploadify.swf',
		'wmode':'transparent',
		'script':'upload.php',
		'scriptData':{'secure':parseCookie('adminSecure')},
		'cancelImg':'_static/'+skinName+'/cancel.png',
		'auto':true,
		'fileDataName':'picture',
		'buttonImg':'_static/'+skinName+'/uppic.png',
		'width':32,
		'height':32,
		'queueID':'upresult',
		'fileDesc':'图片（*.jpg;*.jpeg;*.gif;*.png）',
		'fileExt':'*.jpg;*.jpeg;*.gif;*.png',
		'sizeLimit': 3145728,
		'onSelect':uploadSelect,
		'onComplete':uploadComplete
	});
});

$(window).unload( function () { uploadCancel() } );