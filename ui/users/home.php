<?php

   $auth       = new auth();
   $username   = $auth->authenticated();
   $player     = new player($username);

   $viewport   = new pos(10,6);
   $view       = $player->map->view($viewport);
   $pos        = $player->map->position;
   $img        = $player->img;

?>
<script>
   var player = new playerAPI();
</script>

<section class="map">
   <div class="center">
      <div class="container-fluid map center map-container">
      <div class="map-spacer">&nbsp;</div>
      <?php foreach ($view as $y => $xs) { ?>
         <div class="tile row">
            <?php foreach ($xs as $x => $tile) { ?>
               <?php
                  $class   = ($x == $pos->x and $y == $pos->y)
                     ? 'player'
                     : (($tile['type'] == 'Water') ? null : 'move')
                  ;
               ?>

               <div onclick="player.move(<?=$x;?>, <?=$y;?>);" data-x="<?=$x;?>" data-y="<?=$y;?>" class="<?=$class;?> col-xs-1 <?=$tile['type'];?> tile x<?=$x;?>y<?=$y;?>">
                  <?php if ($img and $class == 'player') {?><img src="<?=$img;?>"> <?php } ?>
               </div>
            <?php } ?>
         </div>
         
      <?php } ?>
      </div>
   </div>
</section>

<section class="header trans map-container">
   <div class="text-white container-fluid">
      <div class="row-fluid">
         <div class="col-sm-6">
            <?=$player->name;?>
            <small class="text-primary">
               <?=$player->status;?>
            </small>
         </div>
         <div class="col-sm-6 right">
            <small class="text-muted">Location:</small>
            <?=$pos;?>
            &nbsp;
            &nbsp;
            Level <?=$player->level();?>
         </div>
   </div>
</section>

<section class="stats trans map-container">
   <div class="text-white container">
      <div class="row">

         <div class="col-xs-3 container padded-md">
            <div class="row">
               <div class="col-xs-2 text-danger">HP</div>
               <div class="col-xs-8">
                  <progress class="hidden-xs stats" max="<?=$player->max_hp;?>" value="<?=$player->cur_hp;?>"></progress>
               </div>
               <div class="col-xs-2">
                  <?=number_format(($player->cur_hp / $player->max_hp) * 100, 0);?>%
               </div>
            </div>
            <div class="row">
               <div class="col-xs-2 text-success">AP</div>
               <div class="col-xs-8">
                  <progress class="hidden-xs stats" max="<?=$player->max_ap;?>" value="<?=$player->cur_ap;?>"></progress>
               </div>
               <div class="col-xs-2">
                  <?=number_format(($player->cur_ap / $player->max_ap) * 100, 0);?>%
               </div>
            </div>
         </div>

         <div class="col-xs-6 padded-md container">
            <div class="row center text-sm">
               <div class="col-xs-2">
                  <div class="action button">
                     1
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button">
                     2
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button">
                     3
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button">
                     4
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button">
                     5
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button">
                     6
                  </div>
               </div>
            </div>   
         </div>

         

         <div class="col-xs-3 padded-md right container">
            <small class="text-sm">
               <div class="row">
                  <div class="col-xs-1">STR</div>
                  <div class="col-xs-4"><?=$player->str;?></div>
               </div>
               <div class="row">
                  <div class="col-xs-1">END</div>
                  <div class="col-xs-4"><?=$player->end;?></div>
               </div>
               <div class="row">
                  <div class="col-xs-1">INT</div>
                  <div class="col-xs-4"><?=$player->int;?></div>
               </div>
            </small>
         </div>

      </div>
   </div>
</section>