<?php

	class map extends gameObject {

		public function __construct($id) {
			$posType 		= new position\type('none');

			parent::__construct('map', 'pge_maps', $id, $posType);

			$tileTable			= $this->table('pge_tiles');
			$selected 			= "(`x` BETWEEN 1 AND {$this->width}) AND (`y` BETWEEN 1 AND {$this->height}) AND `map` = '{$this->id}'";
			$tiles 				= $tileTable->where($selected);

			$tileSet 			= [];
			foreach ($tiles as $t) {
				$position 		= new position\point($t['x'], $t['y'], $t['label']);
				$tile 			= new tile($this, $position);

				$tileSet[$t['y']][$t['x']] = $tile;
			}

			$this->tiles 		= $tileSet;
		}

		public function view($x, $y, $size = 1) {
			$x1	= ($x - $size);
			$y1	= ($y - $size);
			$x2 = ($x + $size);
			$y2 = ($y + $size);

			$tileBuffer			= [];

			for ($curY = $y1; $curY <= $y2; $curY++) {
				for ($curX = $x1; $curX <= $x2; $curX++) {
					$tileBuffer[$y][$x] 	= $this->tile($x, $y);
				}
			}

			return $tileBuffer;
		}

		protected function tile($x, $y) {
			if (isset($this->tiles[$y][$x])) {
				return $this->tiles[$y][$x];
			} else {
				$position 	new position\point($x, $y);
				return new tile($this, $position);
			}
		}

	}

?>