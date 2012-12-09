<?php
	function phpsay_datetime($unixTime)
	{
		$showTime = date('Y',$unixTime)."年".date('n',$unixTime)."月".date('j',$unixTime)."日 ".date('H:i',$unixTime);

		if( date('Y',$unixTime) == date('Y') )
		{
			$showTime = date('n',$unixTime)."月".date('j',$unixTime)."日 ".date('H:i',$unixTime);

			if( date('n.j',$unixTime) == date('n.j') )
			{
				$timeDifference = time() - $unixTime + 1;

				if( $timeDifference < 60 )
				{
					$showTime = $timeDifference."秒前";
				}
				else if($timeDifference >= 60 && $timeDifference < 3600)
				{
					$showTime = floor($timeDifference/60)."分钟前";
				}
				else
				{
					$showTime = "今天 ".date('H:i',$unixTime);
				}
			}
		}

		return $showTime;
	}
?>