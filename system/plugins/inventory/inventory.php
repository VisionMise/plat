<?php

  class inventory extends plugin {
    
    public $size      = 12;
  
    private $owner    = null;
    protected $items  = [];
    
    public function __construct(gameObject $owner, $size = 12) {
      $this->owner  = $owner;
      $this->size   = $size;
      
      parent::__construct('inventory');
    }
    
    public function addItem(gameItem $item) {
      if (count($this->items) >= $this->size) return false;
      
      $this->items[]  = $item;
      return (count($this->items)-1);
    }
    
    public function transfer(inventory $destination, $itemIndex) {
      if (!isset($this->items[$itemIndex])) return false;
      
      $item     = $this->items[$itemIndex];
      $destination->addItem($item);
      $this->dropItem($itemIndex);
      
      return true;
    }
    
    public function dropItem($itemIndex) {
      if (isset($this->items[$itemIndex])) {
        unset($this->items[$itemIndex]);
        return true;
      } else {
        return false;
      }
    }
    
    public function item($itemIndex) {
      return (isset($this->items[$itemIndex]))
        ? $this->items[$itemIndex]
        : null
      ;
    }
    
    public function items() {
      return $this->items;
    }
    
  }