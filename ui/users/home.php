<?php

   $auth       = new auth();
   $username   = $auth->authenticated();
   $player     = new player($username);

   $viewport   = new pos(10,6);
   $view       = $player->map->view($viewport);
   $pos        = $player->map->position;
   $img        = $player->img;

   $miniViewport  = new pos(30,18);
   $miniMap       = $player->map->view($miniViewport);


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

               <div onclick="player.move(<?=$x;?>, <?=$y;?>);" x="<?=$x;?>" y="<?=$y;?>" class="<?=$class;?> col-xs-1 <?=$tile['type'];?> tile x<?=$x;?>y<?=$y;?>">
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
                  <div class="action button inventory" title="Inventory">
                     <span class="text-warning glyphicon glyphicon-th"></span>
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button" title="Stats">
                     <span class="text-primary glyphicon glyphicon-stats"></span>
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button" title="Skills">
                     <span class="text-primary glyphicon glyphicon-list-alt"></span>
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button minimap" title="Area Map">
                     <span class="text-primary glyphicon glyphicon-picture"></span>
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button" title="Players">
                     <span class="text-success glyphicon glyphicon-user"></span>
                  </div>
               </div>
               <div class="col-xs-2">
                  <div class="action button" title="Sign Out">
                     <span class="text-danger glyphicon glyphicon-off"></span>
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

<section class="inventory map-container window">
   <div class="spaceless tight">
      <div class="header">
         <h4 class="text-warning">
            <span class="glyphicon glyphicon-th"></span>
            Inventory
            <span class="text-danger pointer glyphicon pull-right glyphicon-remove"></span>
         </h4>
      </div>
      <div>
         <ul class="nav nav-tabs warning border-bottom border-warning">
            <li class="active">
               <a href="#">Useable</a>
            </li>
            <li>
               <a href="#">Equipable</a>
            </li>
         </ul>
      </div>
   </div>
</section>

<section class="minimap map-container window">
   <div class="spaceless tight">
      <div class="header">
         <h4 class="text-primary">
            <span class="glyphicon glyphicon-picture"></span>
            Mini-Map
            <span class="text-danger pointer glyphicon pull-right glyphicon-remove"></span>
         </h4>
      </div>
      <div class="container-fluid">
         <div class="row-fluid">
            <div class="col-xs-6 col-xs-offset-3">
               <table style="minimap">
                  <tbody>
                     <?php foreach ($miniMap as $y => $xs) { ?>
                        <tr>
                           <?php foreach ($xs as $x => $tile) { ?>
                              <?php $class = ($x == $pos->x and $y == $pos->y) ? 'player' : $tile['type']; ?>
                              <td class="<?=$class;?> sm-tile">
                                 &nbsp;
                              </td>
                           <?php } ?>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>