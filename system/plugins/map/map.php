<?php


	class map extends gameObject {

		public function __construct($id) {
			$posType 		= new position\type('none');

			parent::__construct('map', 'map', $id, $posType);
		}

	}

?>