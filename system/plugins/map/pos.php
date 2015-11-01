<?php

	class pos {

		public $x 		= 0;
		public $y 		= 0;

		public function __construct($x = 0, $y = 0) {
			$this->x 	= $x;
			$this->y 	= $y;

			$this->validate();
		}

		public function __toString() {
			return $this->coords();
		}

		public function __invoke() {
			return [
				'x'		=> $this->x,
				'y'		=> $this->y
			];
		}

		public function coords() {
			return "{$this->x}x{$this->y}";
		}

		private function validate() {
			$x 	= $this->x;
			$y 	= $this->y;

			if (!is_numeric($x)) {
				$x 	= (int) $x;
			} elseif (is_float($x)) {
				$x 	= floor($x);
			}

			if ($x < 0) $x = 0;

			if (!is_numeric($y)) {
				$y 	= (int) $y;
			} elseif (is_float($y)) {
				$y 	= floor($y);
			}

			if ($y < 0) $y = 0;

			$this->x 	= $x;
			$this->y 	= $y;
		}

	}

?>