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

			$playerPos 		= new pos($this->cur_x, $this->cur_y);
			$this->map 		= new map($playerPos);

			$this->calcStats();
		}

		public function moveTo($x, $y) {
			$auth 				= new auth();
			$username 			= $auth->authenticated();
			$uid 				= hash('md5', $username);

			$this->attributes 	= $this->table('pge_player')->record($uid);

			

			if ($x > $this->cur_x) $x = $this->cur_x + 1;
			if ($x < $this->cur_x) $x = $this->cur_x - 1;
			if ($y > $this->cur_y) $y = $this->cur_y + 1;
			if ($y < $this->cur_y) $y = $this->cur_y - 1;

			$tile 				= $this->map->tile(new pos($x, $y));
			if ($tile['type'] != 'Grass') return false;

			

			$update 	= [
				'cur_x'		=> $x,
				'cur_y'		=> $y,
			];

			if (!$this->table('pge_player')->update($uid, $update)) return false;
			
			return true;
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

			$this->attributes['str']	= floor($this->str + (10 / $weight) * ($this->level() * ($weight * $this->str)) / 10);
			$this->attributes['int']	= floor($this->int + (10 / $weight) * ($this->level() * ($weight * $this->int)) / 10);
			$this->attributes['end']	= floor($this->end + (10 / $weight) * ($this->level() * ($weight * $this->end)) / 10);

			$this->attributes['max_hp']	= floor($this->end + (10 / $weight) * ($this->level() * $weight));
			$this->attributes['max_ap']	= 1 + $this->level();
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