<?php

	/**
	 * Version 3.0
	 */
	

	$engine 		= new mapGenerator(500,500);

	exit($engine->generate());



	final class mapGenerator {

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

		private function msg($msg) {
			$msg 		= "+ " . print_r($msg,1) . "\n";
			print $msg;
			return $msg;
		}

		public function generate($water = 0.25, $forest = 0.05) {

			$this->msg("Starting Generator");

			$map 		= $this->buffer();

			$this->addWater($map, $water);
			$this->amplifyWater($map, 2);
			/*$this->amplifyWater($map, 1);

			$this->addForest($map, $forest);
			$this->amplifyForest($map, 2);
			$this->amplifyForest($map, 1);*/

			$this->insertMap($map);
			$this->msg("Done.");
		}

		private function addWater(array &$map, $percentage) {

			$total 			= ($this->xSize * $this->ySize);
			$tiles 			= ceil($total * $percentage);
			$perc 			= floor($percentage * 100);
			$usedCoords 	= [];

			$this->msg("Adding $tiles/$total water tiles $perc%");

			for ($tile = 1; $tile <= $tiles; $tile++) {
				$rndX 		= rand(1, $this->xSize);
				$rndY 		= rand(1, $this->ySize);

				if (isset($usedCoords[$rndX][$rndY]) and $usedCoords[$rndX][$rndY]) {
					$tiles--;
					continue;
				}

				$usedCoords[$rndX][$rndY]		= true;
				$map[$rndX][$rndY]['type']	= 'water';
				$map[$rndX][$rndY]['label']	= 'Water';
			}
		}

		private function amplifyWater(array &$map, $scale = 1) {

			$this->msg("Amplifying water by a factor of $scale");

			for ($x = 1; $x <= $this->xSize -1; $x++) {
				for ($y = 1; $y <= $this->ySize -1; $y++) {
					$tile 		= $map[$x][$y];
					if ($tile['type'] != 'water') continue;

					$x1 		= ($x - $scale);
					$x2 		= ($x + $scale);
					$y1 		= ($y - $scale);
					$y2 		= ($y + $scale);

					for ($cx = $x1; $cx <= $x2; $cx++) {
						for ($cy = $y1; $cy <= $y2; $cy++) {
							$tile 			= $map[$cx][$cy];
							$tile['type']	= 'water';
							$tile['label']	= 'Water';
							$map[$cx][$cy] 	= $tile;
						}
					}
				}
			}
		}

		private function addForest(array &$map, $percentage) {

			$total 			= ($this->xSize * $this->ySize);
			$tiles 			= ceil($total * $percentage);
			$perc 			= floor($percentage * 100);
			$usedCoords 	= [];

			$this->msg("Adding $tiles/$total forest tiles $perc%");

			for ($tile = 1; $tile <= $tiles; $tile++) {
				$rndX 		= rand(1, $this->xSize);
				$rndY 		= rand(1, $this->ySize);

				if (isset($usedCoords[$rndX][$rndY]) and $usedCoords[$rndX][$rndY]) {
					$tiles--;
					continue;
				}

				$usedCoords[$rndX][$rndY]		= true;
				$map[$rndX][$rndY]['type']		= 'forest';
				$map[$rndX][$rndY]['label']		= 'Forest';
			}
		}

		private function amplifyForest(array &$map, $scale = 1) {

			$this->msg("Amplifying forest by a factor of $scale");

			for ($x = 1; $x <= $this->xSize -1; $x++) {
				for ($y = 1; $y <= $this->ySize -1; $y++) {
					$tile 		= $map[$x][$y];
					if ($tile['type'] != 'forest') continue;

					$x1 		= ($x - $scale);
					$x2 		= ($x + $scale);
					$y1 		= ($y - $scale);
					$y2 		= ($y + $scale);

					for ($cx = $x1; $cx <= $x2; $cx++) {
						for ($cy = $y1; $cy <= $y2; $cy++) {
							$tile 			= $map[$cx][$cy];
							$tile['type']	= 'forest';
							$tile['label']	= 'Forest';
							$map[$cx][$cy] 	= $tile;
						}
					}
				}
			}
		}

		private function buffer() {
			$this->msg("Buffering...");
			$buffer 		= [];

			for ($x = -2; $x <= $this->xSize +1; $x++) {
				for ($y = -2; $y <= $this->ySize +1; $y++) {
					$buffer[$x][$y]		= [
						'type'			=> 'grass',
						'label'			=> 'Grass'
					];
				}
			}

			return $buffer;
		}

		private function insertMap(array $map) {
			$this->msg("Adding map to the Database...");

			$index 			= 0;
			foreach ($map as $x => $ys) {
				$index++;
				$perc 		= number_format(($index / count($map)) * 100, 2) . "%";
				$sql 		= "INSERT INTO `pge_tiles` (x,y,type,label) VALUES ";

				$keys 		= array_keys($ys);
				$lastKey 	= $keys[count($keys)-1];

				foreach ($ys as $y => $set) {
					$sql 		.= "($x, $y, '{$set['type']}', '{$set['label']} Tile')";

					if ($y != $lastKey) $sql .= ", ";
				}

				if ($this->db->query($sql)) {
				} else {
					$this->msg("! Error [$sql]");
				}

				$this->msg("$perc complete");
			}
		}

	}


?>