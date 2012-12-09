<?php
class pageAction
{
	function blogPage($total,$per,$page)
	{
		$pagePre = "";

		$pageFirst = "";

		$pageList = "";

		$pageLast = "";

		$pageNext = "";

		$allpage = ceil($total/$per);

		$startcount = $page - 3;

		$endcount = $page + 3;

		if( $startcount < 1 )
		{
			$startcount = 1;
		}

		if( $allpage < $endcount )
		{
			$endcount = $allpage;
		}

		$string = str_replace("index.php","",$_SERVER['PHP_SELF'])."?";

		foreach( $_GET as $k => $v )
		{
			if( $k != "p" )
			{
				$string .= "".$k."=".urlencode($v)."&";
			}
		}

		if( $allpage > 1 )
		{
			if( $page > 1 )
			{
				$pagePre = '<a href="'.$string.'p='.($page-1).'" title="上一页">&lt;&lt;</a>';
			}

			if( $page > 5 )
			{
				$pageFirst = '<a href="'.$string.'p=1" title="首页">1...</a>';
			}

			for( $i = $startcount; $i <= $endcount; $i++ )
			{
				$pageList .= ($page == $i) ? '<strong>'.$i.'</strong>' : '<a href="'.$string.'p='.$i.'">'.$i.'</a>';
			}

			if( $page < ($allpage-4) )
			{
				$pageLast = '<a href="'.$string.'p='.$allpage.'" title="尾页">...'.$allpage.'</a>';
			}

			if( $page < $allpage )
			{
				$pageNext = '<a href="'.$string.'p='.($page+1).'" title="下一页">&gt;&gt;</a>';
			}
		}
		
		$return['pageTotal'] = $allpage;
		
		$return['pagePre'] = $pagePre;
		
		$return['pageFirst'] = $pageFirst;
		
		$return['pageList'] = $pageList;
		
		$return['pageLast'] = $pageLast;
		
		$return['pageNext'] = $pageNext;
		
		return $return;
	}

	function commentPage($id,$total,$per,$page)
	{
		$pageTotal = ceil($total/$per);

		$pagePre = "";

		$pageNext = "";

		if( $pageTotal > 1 )
		{
			if( $page > 1 )
			{
				$pagePre = '<a href="javascript:;" onclick="getComments('.$id.','.($page-1).',1);">上一页</a>&nbsp;&nbsp;';
			}

			if( $page < $pageTotal )
			{
				$pageNext = '&nbsp;&nbsp;<a href="javascript:;" onclick="getComments('.$id.','.($page+1).',1);">下一页</a>';
			}
		}

		$pageArr = array( "pageTotal" => $pageTotal, "pagePre" => $pagePre, "pageNext" => $pageNext );

		return $pageArr;
	}

	function mobilePage($total,$per,$page)
	{
		$string = $_SERVER['PHP_SELF']."?";

		foreach( $_GET as $k => $v )
		{
			if( $k != "p" )
			{
				$string .= "".$k."=".urlencode($v)."&amp;";
			}
		}

		$pageTotal = ceil($total/$per);

		$pagePre = "";

		$pageNext = "";

		if( $pageTotal > 1 )
		{
			if( $page > 1 )
			{
				$pagePre = '<a href="'.$string.'p='.($page-1).'">上一页</a>';
			}

			if( $page < $pageTotal )
			{
				$pageNext = '<a href="'.$string.'p='.($page+1).'">下一页</a>';
			}
		}

		$pageArr = array( "pageTotal" => $pageTotal, "pagePre" => $pagePre, "pageNext" => $pageNext );

		return $pageArr;
	}
}
?>