<?php

  class gameItem extends plugin {
    
    protected $container;
  
    public function __construct(inventory $inventory, $id) {
      $this->container  = $inventory;
      parent::__construct('gameItem', $id);  
    } 
    
  }
  
?>