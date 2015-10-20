<?php

    namespace gameObject;

    abstract class gameObject extends plugin {

        public $name;
        public $type;

        protected $positionType;
        protected $position;

        //database record id
        protected $recordId;

        //can be performed by object
        protected $actions          = [];

        //can be peformed on object
        protected $abilities        = [];

        //modify object attributes
        protected $effects          = [];

        //object properties
        protected $attributes       = [];

        public function __construct($name, $type, $id = 0, position\type $positionType, position\point $location = null) {
            parent::__construct($type);

            $this->name             = $name;
            $this->type             = $type;
            $this->positionType     = $positionType;
            $this->id               = $id;


            if ($this->positionType() == 'map') $this->position = $location;
        }

        public function __toString() {
            return $this->name;
        }

        public function __get($key) {
            return (isset($this->attributes[$key]))
                ? $this->attributes[$key]
                : null 
            ;
        }

        public function __set($key, $value) {
            $this->attributes[$key]     = $value;
        }
    }
?>
