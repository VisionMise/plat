<?php

   $auth       = new auth();
   $username   = $auth->authenticated();
   $player     = new player($username);

   $viewport   = new pos(4,2);
   $view       = $player->map->view($viewport);
   $pos        = $player->map->position;
   $img        = $player->img;

?>
<script>
   var player = new playerAPI();
</script>
<div class="row-fluid">
   <div class="col-xs-12 tight">
      <div class="panel panel-default">
         <div class="panel-body">
            <h4>
               <?=$player->name;?>
               <small><?=$player->status;?></small>
               <div class="pull-right">
                  <small class="text-muted">Location:</small>
                  <?=$pos;?>
                  &nbsp;
                  &nbsp;
                  Level <?=$player->level();?>
               </div>
            </h4>
            <div class="center">
               <div class="container-fluid center map-container">
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
                           <?php if ($img and $class == 'player') {?><img src="<?=$img;?>" style="width:50%;height:50%;position:absolute;top:25%;left:25%;right:25%;bottom:25%;"> <?php } ?>
                        </div>
                     <?php } ?>
                  </div>
                  
               <?php } ?>
               </div>
            </div>
            <div>
               <table class="table">
                  <tbody>
                     <tr class="map-container text-white">
                        <td colspan="2" class="text-white bold">
                           <h4>
                              <span class="text-danger bold">HP</span>
                              <small class="pull-right text-white">
                                 <?=$player->cur_hp;?>
                                 /
                                 <?=$player->max_hp;?>
                                 &nbsp;
                                 <?=number_format(($player->cur_hp / $player->max_hp) * 100, 0);?>%
                              </small>
                              <progress class="stats" max="<?=$player->max_hp;?>" value="<?=$player->cur_hp;?>"></progress>
                           </h4>
                        </td>
                        <td colspan="2">
                           <h4>
                              <span class="text-primary bold">AP</span>
                              <small class="pull-right text-white">
                                 <?=$player->cur_ap;?>
                                 /
                                 <?=$player->max_ap;?>
                                 &nbsp;
                                 <?=number_format(($player->cur_ap / $player->max_ap) * 100, 0);?>%
                              </small>
                              <progress class="stats" max="<?=$player->max_ap;?>" value="<?=$player->cur_ap;?>"></progress>
                           </h4>
                        </td>
                     </tr>
                     <tr>
                        <td class="text-black bold" style="width:25%;">
                           <span class="text-danger bold">STR</span>
                           <span class="pull-right"><?=$player->str;?></span>
                        </td>
                        <td class="text-black bold" style="width:25%;">
                           <span class="text-success bold">END</span>
                           <span class="pull-right"><?=$player->end;?></span>
                        </td>
                        <td class="text-black bold" style="width:25%;">
                           <span class="text-warning bold">INT</span>
                           <span class="pull-right"><?=$player->int;?></span>
                        </td>
                        <td class="text-black bold" style="width:25%;">
                           <span class="text-black bold">XP</span>
                           <span class="pull-right"><?=$player->xp;?></span>
                        </td>
                     </tr>
                  </tobdy>
               </table>
            </div>
         </div>
      </div>
   </div>   
</div>