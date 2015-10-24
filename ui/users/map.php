<pre><?php

	global $pageParam;

	$id 	= $pageParam['map'];
	$map 	= new map($id);

	print_r($map);

?></pre>