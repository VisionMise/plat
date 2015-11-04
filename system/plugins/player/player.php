<?php

	class player extends gameObject {

		public $map;

		public function __construct($username = null) {
			$uid 		= hash('md5', $username);

			parent::__construct('player', $uid);

			if (!$this->loaded) {
				$this->createNewPlayer($username, $uid);
				$this->loaded 	= $this->loadSelf();
			}

			if (!$this->loaded) return false;

			$playerPos 		    = new pos($this->cur_x, $this->cur_y);
			$this->map 		    = new map($playerPos);

			$this->calcStats();
			
			$this->inventory  = new inventory($this, $this->inv_size);
		}

		private function loadPlayer() {
			$auth 				= new auth();
			$username 			= $auth->authenticated();
			$uid 				= hash('md5', $username);

			$this->attributes 	= $this->table('pge_player')->record($uid);
			return $uid;
		}

		public function moveTo($x, $y) {
			$uid 		= $this->loadPlayer();			

			if ($x > $this->cur_x) $x = $this->cur_x + 1;
			if ($x < $this->cur_x) $x = $this->cur_x - 1;
			if ($y > $this->cur_y) $y = $this->cur_y + 1;
			if ($y < $this->cur_y) $y = $this->cur_y - 1;

			$tile 				= $this->map->tile(new pos($x, $y));
			//if ($tile['type'] == 'water') return false;

			$xC		= ($x != $this->cur_x) ? 1 : 0;
			$yC		= ($y != $this->cur_y) ? 1 : 0;
			$reqAp 	= ($xC + $yC);
			$newAp 	= ($this->cur_ap - $reqAp);
			if ($newAp < 0) return false;

			$update 	= [
				'cur_x'		=> $x,
				'cur_y'		=> $y,
				'cur_ap'	=> $newAp
			];

			if (!$this->table('pge_player')->update($uid, $update)) return false;
			
			return true;
		}

		public function move($direction) {
			$uid 		= $this->loadPlayer();
			
			switch (trim(strtolower($direction))) {

				case 'north':
					return $this->moveTo($this->cur_x, $this->cur_y - 1);
				break;

				case 'south':
					return $this->moveTo($this->cur_x, $this->cur_y + 1);
				break;

				case 'west':
					return $this->moveTo($this->cur_x - 1, $this->cur_y);
				break;

				case 'east':
					return $this->moveTo($this->cur_x + 1, $this->cur_y);
				break;
			}

			return false;
		}
		
		public function initActions() {
		  
		  if ($this->ap > 0) $this->addAction('Move', 'moveTo');
		}

		private function createNewPlayer($email, $uid) {
			$dbFormat		= "Y-m-d H:i:s";

			$parts 			= explode("@", $email, 2);
			$name 			= str_replace(['.', '_', '-'], ' ', $parts[0]);
			$name 			= ucwords($name);

			$sPos 			= $this->config['newPlayer']['startingPos'];
			$x 				= rand($sPos['min_x'], $sPos['max_x']);
			$y 				= rand($sPos['min_y'], $sPos['max_y']);

			$newPlayer 		= [
				'id'		=> $uid,
				'email'		=> $email,
				'name'		=> $name,
				'xp'		=> 0,
				'int'		=> 1,
				'str'		=> 1,
				'end'		=> 1,
				'cur_hp'	=> 10,
				'max_hp'	=> 10,
				'cur_ap'	=> 5,
				'max_ap'	=> 5,
				'status'	=> 'New Player',
				'created'	=> date($dbFormat),
				'enabled'	=> true,
				'cur_x'		=> $x,
				'cur_y'		=> $y
			];

			$table 		= $this->table('pge_player');

			if (!$table->insert($newPlayer)) return false;
			return $table->lastId();
		}

		private function calcStats() {
			$template			= $this->config['stats']['level'];

			$weight 			= $template['weight'];

			$this->attributes['str']	    = floor($this->str + (10 / $weight) * ($this->level() * ($weight * $this->str)) / 10);
			$this->attributes['int']	    = floor($this->int + (10 / $weight) * ($this->level() * ($weight * $this->int)) / 10);
			$this->attributes['end']	    = floor($this->end + (10 / $weight) * ($this->level() * ($weight * $this->end)) / 10);

      $this->attributes['inv_size'] = floor(36 + ($this->end / 2));
			$this->attributes['max_hp']	  = floor($this->end + (10 / $weight) * ($this->level() * $weight));
			$this->attributes['max_ap']	  = floor(1 + (($this->int / 2) + ($this->end / 4) * $weight) + (($this->level() / 4) * $weight));
		}

		public function xp($level = 1) {
			$template	= $this->config['stats']['level'];

			$calc 	= 1;
			$base 	= $template['base'];
			$scale 	= $template['scale'];
			$weight = $template['weight'];
			$damp	= 4;

			for ($l = 1; $l < $level; $l++) {
				$calc 	+= floor($l + $base * pow($scale, ($l / $weight)));
			}

			return floor($calc / $damp);
		}

		public function level($xp = false) {
			if (!$xp) 	$xp = $this->xp;
			
			for ($l = 1; $l <= 1024; $l++) {
				$lxp 	= $this->xp($l);
				if ($xp <= $lxp) return ($l -1);
			}

			return -1;
		}

	}

?>