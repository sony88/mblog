<?php
set_time_limit(0);

require_once(dirname(__FILE__)."/global.php");

if( !$loginStat )
{
	if( isset($_POST['secure']) )
	{
		$loginStat = (isLogin($_POST['secure'])) ? 1 : 0;
	}
}

if( isset($_FILES['avatar']) && !empty($_FILES['avatar']['name']) )
{
	if( !$loginStat )
	{
		echo "0";
	}
	else
	{
		if( $_FILES['avatar']['size'] > 1048576 )
		{
			echo "0 图片不能超过1M";
		}
		else
		{
			$imgInfo = @getimagesize($_FILES['avatar']['tmp_name']);

			if( !$imgInfo || !in_array($imgInfo[2], array(1,2,3)) )
			{
				echo "图片无法识别";
			}
			else
			{
				if( $imgInfo[0] < 50 || $imgInfo[1] < 50 )
				{
					die("图片尺寸不符合要求");
				}
				else
				{
					$saveAvatar = $blog_config['avatar_upload']."avatar.jpg";

					if( !move_uploaded_file($_FILES['avatar']['tmp_name'], $saveAvatar) )
					{
						echo "头像上传失败";
					}
					else
					{
						if( $imgInfo[0] != 50 || $imgInfo[1] != 50 || $imgInfo[2] != 2 )
						{
							$image_p = imagecreatetruecolor(50, 50);

							switch($imgInfo[2])
							{
								case 1:
									$image = imagecreatefromgif($saveAvatar);
									break;
								case 2:
									$image = imagecreatefromjpeg($saveAvatar);
									break;
								case 3:
									$image = imagecreatefrompng($saveAvatar);
									break;
							}

							if( $imgInfo[0] > $imgInfo[1] )
							{
								$imgInfo[0] = $imgInfo[0]-($imgInfo[0]-$imgInfo[1]);
							}
							
							if( $imgInfo[0] < $imgInfo[1] )
							{
								$imgInfo[1] = $imgInfo[1]-($imgInfo[1]-$imgInfo[0]);
							}

							imagecopyresampled($image_p, $image, 0, 0, 0, 0, 50, 50, $imgInfo[0], $imgInfo[1]);

							imagejpeg($image_p, $saveAvatar,100);

							imagedestroy($image_p);

							imagedestroy($image);
						}

						echo "1";
					}
				}
			}
		}
	}
}

if( isset($_FILES['picture']) && !empty($_FILES['picture']['name']) )
{
	if( !$loginStat )
	{
		echo "0 login";
	}
	else
	{
		if( $_FILES['picture']['size'] > 3145728 )
		{
			echo "0 图片不能超过3M";
		}
		else
		{
			$imgInfo = @getimagesize($_FILES['picture']['tmp_name']);

			if( !$imgInfo || !in_array($imgInfo[2], array(1,2,3)) )
			{
				echo "0 您上传的不是一张有效的图片";
			}
			else
			{
				switch($imgInfo[2])
				{
					case 1:
						$fileType = ".gif";
						break;
					case 2:
						$fileType = ".jpg";
						break;
					case 3:
						$fileType = ".png";
						break;
				}

				$savePath = date('Y/m/d');

				mkDirs($blog_config['pic_upload'].$savePath);

				$saveName = "_".date('His')."_".rand().$fileType;

				$saveImage = $blog_config['pic_upload'].$savePath."/b".$saveName;

				if( @move_uploaded_file($_FILES['picture']['tmp_name'], $saveImage) )
				{
					createImg($saveImage,$blog_config['pic_upload'].$savePath."/s".$saveName,$imgInfo,230,230);

					if( $imgInfo[0] > 560 || $imgInfo[1] > 560 )
					{
						createImg($saveImage,$saveImage,$imgInfo,560,560);
					}

					echo "1 ".$savePath."/s".$saveName;
				}
				else
				{
					echo "0 上传失败";
				}
			}
		}
	}
}

if( isset($_POST['deletePic']) && !empty($_POST['deletePic']) )
{
	if( $loginStat )
	{
		delPicture($_POST['deletePic']);
	}
}

ob_end_flush();
?>