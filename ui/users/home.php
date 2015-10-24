<?php

  $auth   = new auth();
  $email  = $auth->getUserByToken($_SESSION['token']);
  $user   = $auth->getUser($email);

  $player = new player($email);

 
  $xp     = $player->info['xp'];
  $lvl    = $player->level();
  $next   = $player->xpFromLevel($lvl + 1) - $xp;

  $skills = $player->skillList;

?>
<div class="row-fluid">
  <div class="col-sm-10 col-sm-offset-1 col-xs-12 tight">

    <div class="panel panel-inverse trans">
      
      <div class="panel-body bg-white">

        <section class="menu">
          <h4 class="left">
            <?=$player->info['name'];?>
            <small class="pull-right"><?=$player->info['age'];?> day old Level <?=$lvl;?> <?=$player->info['status'];?></small>
          </h4>
          <h5>
          <ul class="right nav nav-tabs" style="border-bottom:solid 1px #ddd;">
            <li class="tab-button pull-right ">
              <a href="#" onclick="tab('inv', $(this).parent());">
                <span class="glyphicon glyphicon-th"></span>
                Inventory
              </a>
            </li>
            <li class="tab-button pull-right ">
              <a href="#" onclick="tab('skills', $(this).parent());">
                <span class="glyphicon glyphicon-list"></span>
                Skills
              </a>
            </li>
            <li class="tab-button pull-right ">
              <a href="#" onclick="tab('stats', $(this).parent());">
                <span class="glyphicon glyphicon-stats"></span>
                Stats
              </a>
            </li>
            <li class="tab-button pull-right active">
              <a href="#" onclick="tab('main', $(this).parent());">
                <span class="glyphicon glyphicon-home"></span>
                Main
              </a>
            </li>
          </ul>
          </h5>
        </section>

        <section class="player_card">

          <br class="hidden-xs">

          <div class="tab-pane active main">
            <div class="row-fluid">
              <div class="col-sm-6">
                <h2 >
                  <span class="pull-left">
                    <i class="fa fa-bars"></i>
                    Level <?=$lvl;?>
                    <span class="text-muted"><?=$player->info['status'];?></span>
                  </span>
                  <br>
                </h2>
                <hr>
                <div class="">
                  <h4>
                    <?=$xp;?>
                    <small>XP</small>
                  </h4>
                  <h4>
                    <?=$next;?>
                    <small>XP until next level</small>
                  </h4>
                  <h4>
                    <?=$player->info['age'];?> Days Old
                    <small>
                      Alive since
                      <?=date("l, F jS Y", strtotime($player->info['created'])); ?>
                    </small>
                  </h4>
                </div>
              </div>
              <hr class="visible-xs">
              <div class="col-sm-6 tight panel panel-success">
                <div class="panel-heading">
                  <h4>
                    <i class="pull-right fa fa-medkit"></i>
                    Player Health
                  </h4>
                </div>
                <div class="center panel-body">
                  <h2>
                    <?=$player->info['cur_hp'];?>
                    /
                    <?=$player->info['max_hp'];?>
                    <span class="glyphicon glyphicon-heart"></span>
                    <small>HP (<?=number_format(($player->info['cur_hp'] / $player->info['max_hp']) * 100, 0);?>%)</small>
                  </h2>
                  <h2>
                    <?=$player->info['cur_ap'];?>
                    /
                    <?=$player->info['max_ap'];?>
                    <span class="glyphicon glyphicon-flash"></span>
                    <small>AP (<?=number_format(($player->info['cur_ap'] / $player->info['max_ap']) * 100, 0);?>%)</small>
                  </h2>
                </div>
              </div>
            </div>
          </div>
          
          <div class="tab-pane stats" style="display:none;">
            <div class="row">

              <div class="col-sm-6">
                <div class="">
                  <h4>Base Stats</h4>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    
                    <tbody>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-male"></i></h2></td>
                        <th>
                          <h5>
                            Strength
                            <small class="hidden-xs">
                              <br>
                              Strength increases Damage and Defense
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;">
                          <h4 class="right">
                            <?=$player->info['strength'];?>
                          </h4>
                        </td>
                      </tr>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-lightbulb-o"></i></h2></td>
                        <th>
                          <h5>
                            Intelligence
                            <small class="hidden-xs">
                              <br>
                              Intelligence increases Action Points and Luck
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;"><h4 class="right"><?=$player->info['intelligence'];?></h4></td>
                      </tr>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-heartbeat"></i></h2></td>
                        <th>
                          <h5>
                            Endurance
                            <small class="hidden-xs">
                              <br>
                              Endurance increases Defence and Hit Points
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;"><h4 class="right"><?=$player->info['endurance'];?></h4></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="">
                  <h4>
                    Ability
                  </h4>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    
                    <tbody>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-crosshairs"></i></h2></td>
                        <th>
                          <h5>
                            Damage
                            <small class="hidden-xs">
                              <br>
                              Higher Strength increases Damage
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;"><h4 class="right"><?=$player->info['damage'];?></h4></td>
                      </tr>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-shield"></i></h2></td>
                        <th>
                          <h5>
                            Defense
                            <small class="hidden-xs">
                              <br>
                              Strength and Endurance increase Defence
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;"><h4 class="right"><?=$player->info['defense'];?></h4></td>
                      </tr>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-thumbs-o-up"></i></h2></td>
                        <th>
                          <h5>
                            Luck
                            <small class="hidden-xs">
                              <br>
                              All base states increase luck. Being specialized is best
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;"><h4 class="right"><?=$player->info['luck'];?></h4></td>
                      </tr>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-heart"></i></h2></td>
                        <th>
                          <h5>
                            Hit Points
                            <small class="hidden-xs">
                              <br>
                              Hit points increase with level, but Endurance helps
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;"><h4 class="right"><?=$player->info['max_hp'];?></h4></td>
                      </tr>
                      <tr>
                        <td style="width:80px;"><h2 class="spaceless tight center"><i class="fa fa-bolt"></i></h2></td>
                        <th>
                          <h5>
                            Action Points
                            <small class="hidden-xs">
                              <br>
                              Action points are based on Intelligence, Level and Luck
                            </small>
                          </h5>
                        </th>
                        <td style="width:80px;"><h4 class="right"><?=$player->info['max_ap'];?></h4></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane skills" style="display:none">
            <div class="row">
              <div class="col-sm-8">
                <div class="">
                  <h4>Skills</h4>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    
                    <tbody>
                      <?php foreach ($skills as $skill => $meta) { ?>
                        <tr>
                          <td>
                            <h2 class="center tight spaceless"><i class="fa fa-<?=$meta['icon'];?>"></i></h2>
                          </td>
                          <th>
                            <h5>
                              <?=$skill;?>
                              <br>
                              <small>
                                <?=$meta['text'];?>
                              </small>
                            </h5>
                          </th>
                          <td>
                            <h4>
                              <small>Level</small>
                              <?=(int) $player->skills($skill);?>
                              /
                              <?=$meta['max'];?>
                            </h4>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane inv" style="display:none">
            <div class="row">
              <div class="col-sm-12">
                <h4>
                  Inventory
                </h4>
                <div>

                </div>
              </div>
            </div>
          </div>

        </section>

        <button class="play_button btn btn-primary">Play</button>

      </div>
    </div>

  </div>
</div>
<script src="./assets/js/extensions/pge/pge_player.js"></script>
<script>
  function tab(tabname, $parent) {

    $('.tab-pane').not('.' + tabname)
      .removeClass('active')
      .hide()
    ;

    $('.tab-button').not($parent).removeClass('active');
    $parent.addClass('active');

    $('.' + tabname)
      .addClass('active')
      .show()
    ;
  }

  $('.play_button').on('click', function(event) {
    gsAPI.page('map', {"map": 1}, '#content', function(data) {

    });
  });
</script>