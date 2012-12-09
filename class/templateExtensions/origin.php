<?php
	function phpsay_origin($from)
	{
		switch(strtolower($from))
		{
			case 'web':
				$origin = "Web";
				break;
			case 'wap':
				$origin = "Wap";
				break;
			case 'api':
				$origin = "API";
				break;
		}
		
		return $origin;
	}
?>