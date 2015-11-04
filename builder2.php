<?php

	$engine 		= new mapBuilder2(500, 500);

	$engine->generateMap();

	final class mapBuilder2 {

		private $xSize;
		private $ySize;

		public $db;

		public function __construct($xSize, $ySize) {
			$this->xSize 	= $xSize;
			$this->ySize 	= $ySize;

			$this->connect();
		}

		private function connect() {
			$this->db 	= new mysqli('localhost', 'pge', 'af3264ac7', 'pge');
		}

		public function generateMap() {
			print "\n";
			print "Creating initial map (first pass) ...\n";
			$initialPassMap 		= $this->generateMemoryMap();

			print "Adding water bodies to map (second pass) ...\n";
			$waterfiedMap 			= $this->generateWater($initialPassMap, 0.05);
			
			print "Amplifying water bodies to map (third pass) ...\n";
			$waterfiedMap 			= $this->generateWater($waterfiedMap, 0.25);			

			print "Adding forest to map (fourth pass) ...\n";
			$treeifiedMap 			= $this->generateForests($waterfiedMap, 0.5);

			
			print "Removing small bodies of water (fifth pass) ...\n";
			$driedMap 				= $this->removeWater($treeifiedMap);

			print "Removing small islands of grass (sixth pass) ...\n";
			$driedMap 				= $this->removeIslands($driedMap);

			print "Removing small bodies of water (seventh pass) ...\n";
			$driedMap 				= $this->generateWater($driedMap, 0.5);



			$bufferedMap 			= $driedMap;


			print "Adding to database...\n";
			$index 			= 0;
			foreach ($bufferedMap as $x => $ys) {
				$index++;
				$perc 		= number_format(($index / count($waterfiedMap)) * 100, 2) . "%";
				$sql 		= "INSERT INTO `pge_tiles` (x,y,type,label) VALUES ";

				$keys 		= array_keys($ys);
				$lastKey 	= $keys[count($keys)-1];

				foreach ($ys as $y => $set) {
					$sql 		.= "($x, $y, '{$set['type']}', '{$set['label']} Tile')";

					if ($y != $lastKey) $sql .= ", ";
				}

				if ($this->db->query($sql)) {
				} else {
					print "! Error [$sql]\n";
				}

				print "\t $perc complete\n";
			}
			
			print "Done.\n";
		}

		private function generateWater(array $map, $perc) {

			$perc 	= ($perc / 100);
			$total 	= ($this->xSize * $this->ySize);
			$tiles 	= ceil($total * $perc);

			for ($i = 1; $i <= $tiles; $i++) {
				$rndX 		= rand(1, $this->xSize);
				$rndY 		= rand(1, $this->ySize);

				$map[$rndX][$rndY]['type']	= 'water';
				$map[$rndX][$rndY]['label']	= 'Water';
			}

			foreach ($map as $x => $ys) {
				foreach ($ys as $y => $set) {

					$type 		= $set['type'];
					if ($type != 'water') continue;

					$x1 		= $x - 3;
					$x2 		= $x + 3;
					$y1 		= $y - 3;
					$y2 		= $y + 3;

					for ($cx = $x1; $cx <= $x2; $cx++) {
						for ($cy = $y1; $cy <= $y2; $cy++) {
							$roll 	= rand(1,8);
							if ($roll < 6) {
								if ($cx > 0 and $cy > 0 and $cx <= $this->xSize and $cx <= $this->ySize) {
									$map[$cx][$cy]['type']	= 'water';
									$map[$cx][$cy]['label']	= 'Water';	
								}
							}
						}
					}
				}
			}

			return $map;
		}

		private function removeIslands(array $map) {
			foreach ($map as $x => $ys) {
				foreach ($ys as $y => $set) {

					$type 		= $set['type'];
					if ($type != 'grass') continue;

					$x1 		= $x - 3;
					$x2 		= $x + 3;
					$y1 		= $y - 3;
					$y2 		= $y + 3;

					$count 		= 0;

					for ($cx = $x1; $cx <= $x2; $cx++) {
						for ($cy = $y1; $cy <= $y2; $cy++) {
							if (!isset($map[$cx][$cy]['type'])) continue;
							if ($map[$cx][$cy]['type'] == 'grass') $count++;
						}
					}

					$x1 		= $x - 3;
					$x2 		= $x + 3;
					$y1 		= $y - 3;
					$y2 		= $y + 3;

					if ($count <= 2) {
						for ($cx = $x1; $cx <= $x2; $cx++) {
							for ($cy = $y1; $cy <= $y2; $cy++) {
								if (!isset($map[$cx][$cy]['type'])) continue;
								if ($cx > 0 and $cy > 0 and $cx <= $this->xSize and $cx <= $this->ySize) {
									$map[$x][$y]['type']	= 'water';
									$map[$x][$y]['label']	= 'Water';
								}
							}
						}
					}

				}
			}

			return $map;
		}


		private function removeWater(array $map) {
			foreach ($map as $x => $ys) {
				foreach ($ys as $y => $set) {

					$type 		= $set['type'];
					if ($type != 'water') continue;

					$x1 		= $x - 3;
					$x2 		= $x + 3;
					$y1 		= $y - 3;
					$y2 		= $y + 3;

					$count 		= 0;

					for ($cx = $x1; $cx <= $x2; $cx++) {
						for ($cy = $y1; $cy <= $y2; $cy++) {
							if (!isset($map[$cx][$cy]['type'])) continue;
							if ($map[$cx][$cy]['type'] == 'water') $count++;
						}
					}

					$x1 		= $x - 3;
					$x2 		= $x + 3;
					$y1 		= $y - 3;
					$y2 		= $y + 3;

					if ($count <= 2) {
						for ($cx = $x1; $cx <= $x2; $cx++) {
							for ($cy = $y1; $cy <= $y2; $cy++) {
								if (!isset($map[$cx][$cy]['type'])) continue;
								if ($cx > 0 and $cy > 0 and $cx <= $this->xSize and $cx <= $this->ySize) {
									$map[$x][$y]['type']	= 'forest';
									$map[$x][$y]['label']	= 'Forest';
								}
							}
						}
					}

				}
			}

			return $map;
		}

		private function generateForests(array $map, $perc) {

			$perc 	= ($perc / 100);
			$total 	= ($this->xSize * $this->ySize);
			$tiles 	= ceil($total * $perc);

			for ($i = 1; $i <= $tiles; $i++) {
				$rndX 		= rand(1, $this->xSize);
				$rndY 		= rand(1, $this->ySize);

				$map[$rndX][$rndY]['type']	= 'forest';
				$map[$rndX][$rndY]['label']	= 'Forest';
			}

			foreach ($map as $x => $ys) {
				foreach ($ys as $y => $set) {

					$type 		= $set['type'];
					if ($type != 'grass') continue;

					$x1 		= $x - 3;
					$x2 		= $x + 3;
					$y1 		= $y - 3;
					$y2 		= $y + 3;

					for ($cx = $x1; $cx <= $x2; $cx++) {
						for ($cy = $y1; $cy <= $y2; $cy++) {
							$roll 	= rand(1,800);
							if ($roll < 5) {
								if ($cx > 0 and $cy > 0 and $cx <= $this->xSize and $cx <= $this->ySize) {
									$map[$cx][$cy]['type']	= 'forest';
									$map[$cx][$cy]['label']	= 'Forest';	
								}
							}
						}
					}
				}
			}

			return $map;
		}

		private function generateMemoryMap() {
			$buffer 		= [];

			for ($x = -2; $x <= $this->xSize +1; $x++) {
				for ($y = -2; $y <= $this->ySize +1; $y++) {
					$type 				= $this->initialPassType($x, $y);
					$buffer[$x][$y]		= [
						'type'			=> $type,
						'label'			=> ucwords($type)
					];
				}
			}

			return $buffer;
		}


		private function initialPassType($x, $y) {
			if ($x <= 0 or $y <= 0) {
				return 'edge';
			} elseif ($x > $this->xSize or $y > $this->ySize) {
				return 'edge';
			} elseif ($x == 1 or $y == 1) {
				return 'water';
			} else {
				return 'grass';
			}
		}

	}


?>
