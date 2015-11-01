<?php


	$maxSize 			= 200;

	$cnt 			= [
		'Grass'	=> 0,
		'Water'	=> 0
	];

	$max 			= ($maxSize * $maxSize);
	$db  			= new mysqli('localhost', 'pge', 'af3264ac7', 'pge');
	$c 				= 0;
	$strBuffer 		= "";

	$startTime 	= microtime(true);

	for ($y = 1; $y <= $maxSize; $y++) {
		for ($x = 1; $x <= $maxSize; $x++) {

			$c++;
			$rnd 			= rand(1, 20);
			if ($rnd < 19) {
				$type 		= 'Grass';
			} else {
				$type 		= 'Water';
			}

			$strBuffer	   .= "NULL, $x, $y, '', '$type'\n";
			$size 			= number_format(strlen($strBuffer) / 1024,2) . "KB";
			$sql 			= "INSERT INTO `pge_tiles` (x,y,type) VALUES ($x, $y, '$type');";
			$runTime 		= number_format((microtime(true)-$startTime), 4);
			$perc 			= number_format(($c / $max) * 100, 0);
			
			if ($db->query($sql)) {
				print "> Inserted [{$c} Tiles/$max {$perc}%]  {$size} ($runTime Seconds)  {$x}x{$y}\n";
			} else {
				print "! Error [$sql]";
			}

			$cnt[$type]	+= 1;
		}
	}


	$path 		= 'generated.csv';
	$size 		= number_format(file_put_contents($path, $strBuffer) / 1024);
	$total 		= ($cnt['Water'] + $cnt['Grass']);
	$gp			= number_format(($cnt['Grass'] / $total) * 100, 2);
	$wp			= number_format(($cnt['Water'] / $total) * 100, 2);
	
	$endTime	= microtime(true);
	$runTime 	= number_format(($endTime-$startTime), 4);

	$msg 	= 
		"\n".
		"Generated ".($x-1)."x".($y-1)." Map\n".
		"{$cnt['Grass']} Grass Tiles ({$gp}%)\n".
		"{$cnt['Water']} Water Tiles ({$wp}%)\n".
		"$total Total Tiles\n".
		"Wrote {$size}KB File to $path\n".
		"Took $runTime Seconds\n\n"
	;

	exit($msg);

?>