<?php

	class map extends gameObject {

		public $position;

		public function __construct(pos $position) {
			$this->position 	= $position;

			parent::__construct('map');
		}

		public function tile(pos $position) {

			$x 			= $position->x;
			$y 			= $position->y;

			$where    	= "WHERE `x` = $x AND `y` = $y";
			$tile 		= $this->table('pge_tiles')->select($where);
			if (isset($tile['id'])) $tile = [$tile];

			return $tile[0];
		}

		public function view(pos $xy = null) {

			if (!$xy) {
				$x_size 		= $this->config['view']['size'];
				$y_size 		= $this->config['view']['size'];
			} else {
				$x_size 		= $xy->x;
				$y_size 		= $xy->y;
			}

			$xs 		= [];
			$ys 		= [];

			for ($cx = $this->position->x -$x_size; $cx <= $this->position->x + $x_size; $cx++) {$xs[] = $cx;}
			for ($cy = $this->position->y -$y_size; $cy <= $this->position->y + $y_size; $cy++) {$ys[] = $cy;}
			$xStr 		= implode(", ", $xs);
			$yStr 		= implode(", ", $ys);


			$table 		= $this->table('pge_tiles');
			$where 		= "SELECT * FROM `pge_tiles` WHERE x IN ($xStr) AND y IN ($yStr) ORDER BY y, x ASC";

			$tiles 		= $table->query($where);
			$buffer 	= [];

			foreach ($tiles as $tile) {
				$x 	= $tile['x'];
				$y 	= $tile['y'];

				$buffer[$y][$x]	= $tile;
			}

			return $buffer;
		}
		
	}

?>