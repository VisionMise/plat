<?php

	abstract class gameObject extends plugin {

		protected $type;
		protected $label;
		protected $id;
		protected $attributes		= [];

		private $loaded;

		public function __construct($type, $id = 0, $label = null) {
			$this->type 		= $type;
			$this->id 			= $id;
			$this->label 		= $label;

			parent::__construct($type);

			$this->loaded		= $this->loadSelf();
		}

		public function __get($key) {
			return (isset($this->attributes[$key]))
				? $this->attributes[$key]
				: null
			;
		}

		protected function loadSelf() {
			if (!$this->id) 	return false;

			$table 				= $this->table("pge_{$this->type}");
			if (!$table) 		return false;

			$record 			= $table->record($this->id);
			if (!$record) 		return false;

			$this->attributes 	= $record;
			return true;
		}

	}


?>