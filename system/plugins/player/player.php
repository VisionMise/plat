<?php

	class player extends plugin {

		public $info			= [];

		private $ready			= false;
		
		protected $email		= null;
		protected $table 		= null;
		protected $core			= null;

		public function __construct($email = null) {
			global $core;

			parent::__construct('player');

			$auth 				= new auth();
			$this->email 		= ($email)
				? $email
				: $auth->getUserByToken($_SESSION['token'])
			;

			$this->core 		=&$core;
			$this->ready 		= $this->init();
			$this->calcStats();
		}




		private function init() {
			$this->table 		= $this->table('pge_players');
			if (!$this->table) return false;

			$this->info 		= $this->table->where("`email` = '{$this->email}' ORDER BY `created` DESC LIMIT 1;");

			if (!$this->info) $this->create_base_player();
			if (!$this->info) return false;

			$this->core->logger->logMessage("Loaded Player {$this->info['name']}");
			$this->core->notes->add("Loaded Player {$this->info['name']}", "Your player has been loaded");

			return true;
		}

		private function create_base_player() {
			$base 				= $this->templates['base_player'];
			$form 				= "Y-m-d H:i:s";

			$template 			= [
				'name'		=> $this->email,
				'cur_hp'	=> $base['max_hp'],
				'cur_ap'	=> $base['max_ap'],
				'status'	=> 'Noob',
				'created'	=> date($form),
				'enabled'	=> 1,
				'email'		=> $this->email,
				'level'		=> 1
			];

			$template 			= array_merge($template, $base);
			$keys 				= array_keys($template);
			$keyStr 			= "`" . implode("`, `", $keys) . "`";
			$values 			= array_values($template);
			$valStr 			= "'" . implode("', '", $values) . "'";

			$insert 			= "INSERT INTO `{$this->table->name}` ($keyStr) VALUES ($valStr);";
			$this->table->query($insert);

			$this->core->logger->logMessage("Created New Player");
			$this->core->notes->add("Created New Player", "Congrats! A new player has just been created for you!");

			$this->info 	= $this->table->where("`email` = '{$this->email}' ORDER BY `created` DESC LIMIT 1;");
			if (!$this->info) return false;

			return $this->info;
		}


		private function calc_damage($str) {
			$values 	= $this->templates;
			$scale 		= (float) $values['dmg_scale'];

			$lvl 		= $this->level();
			$base 		= $str * ($scale ^ $lvl);
			$min 		= 1;

			//print "<pre>damage $str * ($scale ^ $lvl) = $base</pre>";

			$damage 	= $min + $base;
			return floor($damage);
		}

		private function calc_defense($str, $end) {
			$values 	= $this->templates;
			$scale 		= (float) $values['def_scale'];

			$lvl 		= $this->level();
			$base 		= (($str / 4) + $end) * ($scale ^ $lvl);
			$min 		= 1;

			//print "<pre>defense ($end + ($str / 4) * ($scale ^ $lvl) = $base</pre>";

			$defense 	= $min + $base;
			return floor($defense);
		}

		private function calc_luck($str, $end, $int) {
			$values 	= $this->templates;
			$scale 		= (float) $values['luck_scale'];

			$lvl 		= $this->level();
			$base 		= ((($str / 4) + ($end / 3) + $int) / 3) * ($scale ^ ($lvl / 2));
			$min 		= 1;

			//print "<pre>luck (($str / 4) + ($end / 3) + ($int) / 3) * ($scale ^ ($lvl / 2)) = $base</pre>";

			$luck 		= $min + $base;
			return floor($luck);
		}

		private function calc_hp($maxHp, $end) {
			$values 	= $this->templates;
			$scale 		= (float) $values['hp_scale'];

			$lvl 		= $this->level();
			$base 		= ($end) * ($scale ^ $lvl);
			$min 		= $maxHp;

			//print "<pre>hp ($end * ($scale ^ $lvl)) = $base</pre>";

			$maxhp 		= $min + $base + $lvl;
			return floor($maxhp);
		}

		private function calc_ap($maxAp, $int) {
			$values 	= $this->templates;
			$scale 		= (float) $values['ap_scale'];

			$lvl 		= $this->level();
			$base 		= ($int) * ($scale ^ $lvl);
			$min 		= $maxAp;

			//print "<pre>ap ($int * ($scale ^ $lvl)) = $base</pre>";

			$maxap 		= $min + $base + ($lvl / 4);
			return floor($maxap);
		}

		private function calcStats() {

			$str 		= $this->info['str'];
			$int 		= $this->info['int'];
			$end 		= $this->info['end'];
			$maxHp 		= $this->info['max_hp'];
			$maxAp 		= $this->info['max_ap'];

			$str_scale 	= $this->templates['str_scale'];
			$int_scale 	= $this->templates['int_scale'];
			$end_scale 	= $this->templates['end_scale'];

			$lvl 		= $this->level();
			$age_sec   	= time() - strtotime($this->info['created']);
			$age 		= floor(($age_sec / 3600) / 24);
			
			$str 	= ($str * ($lvl * $str_scale));
			$int 	= ($int * ($lvl * $int_scale));
			$end 	= ($end * ($lvl * $end_scale));
			
			$stats 		= [
				'age'			=> $age,
				'strength'		=> floor($str),
				'intelligence'	=> floor($int),
				'endurance'		=> floor($end),
				'luck'			=> $this->calc_luck($str, $end, $int),
				'damage'		=> $this->calc_damage($str),
				'defense'		=> $this->calc_defense($str, $end),
				'max_ap'		=> $this->calc_ap($maxAp, $int),
				'max_hp'		=> $this->calc_hp($maxHp, $end)
			];

			foreach ($stats as $stat => $val) {
				$this->info[$stat]	= $val;
			}
		}





		protected function addXP($xpAmount) {
			$this->info['xp']	+= $xpAmount;
			$update 			= 
				"UPDATE `{$this->table->name}` SET `xp` = {$this->info['xp']} WHERE `email` = '{$this->email}' AND `id` = {$this->info['id']};"
			;

			$this->table->query($update);
			$this->init();

			$this->core->logger->logMessage("Player XP Gained");
			$this->core->notes->add("XP Gained", "You have just gained $xpAmount XP!");
			return true;
		}


		public function level() {
			$xp     	= $this->info['xp'];
			$maxLevel	= $this->templates['max_level'];

			for ($lvl = 1; $lvl <= $maxLevel; $lvl++) {
				$minXP	= $this->xpFromLevel($lvl);

				if ($minXP > $xp) 	return $lvl;
				if ($minXP == $xp) 	return $lvl + 1;
			}

			return $maxLevel;
		}

		public function skills($skill) {
			return 0;
		}

		/** API */
		public function info($key = null) {
			return ($key)
				? ((isset($this->info[$key]))
					? $this->info[$key]
					: null
				)
				: $this->info
			;
		}

		




		public function setName($name) {
			$update 		= "UPDATE `{$this->table->name}` SET `name` = '$name' WHERE `id` = {$this->info['id']} AND `email` = '{$this->email}';";
			$this->table->query($update);

			$this->core->logger->logMessage("Updated Player Name");
			$this->core->notes->add("Updated Player Name", "You have just changed your player's name");

			$this->info 	= $this->table->where("`email` = '{$this->email}' ORDER BY `created` DESC LIMIT 1;");
			if (!$this->info) return false;

			return $this->info;
		}

		public function xpFromLevel($level) {
			$values 	= $this->templates;

			$lvlScale 	= (float) $values['level_scale'] 	* 10;
			$lvlBase 	= (float) $values['level_base'] 	* 10;

	  		$lvlBase	= ($level * $lvlBase);
	  		$scale 		= ($lvlScale * $level) ^ $level;
	  		$xp 		= ($lvlBase * $scale);

	  		//print "<pre>[$level] = $scale * $lvlBase = $xp\n</pre>";
  			
	    	return floor($xp);
	  	}

	  	
	}

?>