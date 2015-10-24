<?php
	
	class tile extends gameObject {

		public $location;

		public function __construct(map $parent, position\point $location) {
			$posType 		= new position\type('map');
			$this->parent 	= $parent;
			$this->location = $location;
			$this->coords 	= "{$this->location->x}x{$this->location->y}";

			parent::__construct('tile', 'tile', 0, $posType, $this->location);
		}

		public function x() 	{return $this->location->x;}
		public function y() 	{return $this->location->y;}
		public function label() {return $this->location->label;}
	}
?>