<?php

	namespace position;

    final class type {
        public $key;
        public $value;
        public $parent;

        public function __construct($typeName) {
            $this->parent         = $typeName;
        }

        public function __invoke() {
            return $this->key;
        }

        public function __toString() {
        	return $this->key;
        }

        private function validate() {

            switch (trim(strtolower($this->parent))) {

                case 'inventory':
                case 'item':
                case 'player':
                case 'npc':
                    $this->key      = 'inventory';
                    $this->value    = 1;
                break;

                default:
                case 'map':
                    $this->key      = 'map';
                    $this->value    = 0;
                break;

                case 'none':
                    $this->key      = 'none';
                    $this->value    = -1;
                break;
            }
        }
    }

    final class point {

        public $x;
        public $y;
        public $label;

        public function __construct($x, $y, $label = null) {
            $this->x    = $x;
            $this->y    = $y;
            $this->label= $label;
        }
    }
?>