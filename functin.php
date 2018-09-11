<?php
//二叉树生成
function generateTree($arr,$parentName)
{
	$refer  = [];
	$tree   = [];

	foreach($arr as $key => $val)
	{
		$refer[$val['value']] = &$arr[$key];
	}
	
	foreach($arr as $key=>$val)
	{
		$pid = $val[$parentName];

		if($pid == 0)
		{
			$tree[] = &$arr[$key];
		}else{
			if( isset($refer[$pid]) )
			{
				$refer[$pid]['children'][] = &$arr[$key];
			}
			print_r($arr);
		}

	}

	return $tree;
}



?>