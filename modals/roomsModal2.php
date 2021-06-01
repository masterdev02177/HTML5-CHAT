<div class="modal fade" id="roomsModal" data-keyboard="false" data-keyboard="false"  data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close']?></span></button>
                <h4 class="modal-title rooms-modal"><?=$traductions['rooms']?></h4>
                <p><?=$traductions["Pick a room"]?></p>
                <?php if ($config->showSearchRoom):?>
                    <div id="searchRoomContainer" class="flex-property search-bar">
                        <i class="fa fa-search"></i>
                        <input id="searchInputRoom2" class="searchInputRoom" type="text" placeholder="<?=$traductions['searchRoom']?>" autocomplete="off">
                        <?php if ($config->showSearchRoomAdultCheckbox):?>
                            <span class="buttons-subnames flex-property"><span><?=$traductions['adultRoom']?></span></span>
                            <input type="checkbox" id="adultRoomCheckBox2" checked
                                   data-toggle="toggle"
                                   data-on=""
                                   data-off=""
                                   data-onstyle="success"
                                   data-offstyle="danger">
                        <?php endif?>
                    </div>
                <?php endif?>
            </div>
            <div class="modal-body">
                <div id="roomContainer">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="75%"><?=$traductions['name']?></th>
                            <th width="80px"><?=$traductions['chatters']?></th>
                            <th><?=$traductions['action']?></th>
                        </tr>
                        </thead>
                        <tbody id="tableRoomBody">

                        <!-- <div class="oneRoomDiv flex-property">
                          <div class="roomsLeftInfo flex-property">
                            <h2 class="roomTitle">Name of room</h2>
                            <p class="roomDesc">Description of group</p>
                            <p class="roomUsers">Anime par <a href="#">[Pseudonyme]</a>, <a href="#">[Pseudonyme]</a>, <a href="#">[Pseudonyme]</a></p>
                          </div>
                          <div class="roomsRightInfo flex-property">
                            <p class="right-subtitle">XX doctinautes <span>en linge</span></p>
                            <a href="#" class="joinRoomModal">Rejoindre</a>
                          </div>
                        </div> -->


                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <?php if ($roles[$myuser['role']]->canCreateDynamicRoomNumber && $config->multiRoomEnter=='1' ): ?>
                    <button class="btn  btn-default createRoomBtn"><i class="fa fa-plus-circle"></i> <?=$traductions['createMyRoom']?></button>
                <?php endif?>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close']?></button>
            </div>
        </div>
    </div>
</div>

