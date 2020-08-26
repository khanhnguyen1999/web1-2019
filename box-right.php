<?php $allfriends = getAll($currentuser['userID']);
    $allf = getAlls($currentuser['userID']);
?>
            <div id="fixed_container" class="col-md-3-box-right d-none d-md-block">
                <!-- <div class="position-fixed" style="width:255px" id="fixed_box_right"> -->
                <div class="position-fixed" id="fixed_container_position">
                    <div class="card_profile gedf-card">
                        <div class="card-body-profile-info ui_rmenu">
                            <div class="card-title ui_rmenu_item_sel">Thành viên<small  class="text-muted">
                            <?php 
                            $counting = 0;
                            foreach($allf as $alls)
                            {
                                $counting++;
                            }
                            ?>
                            </small><?php echo $counting;?></div>
                                <div class="justify-content-between align-items-center" style="margin-left:12px;">
                                    <?php foreach($allfriends as $all):?>
                                        <div class="testing" style="display:flex;margin-bottom:10px;">
                                            <div class="mr-2">
                                                <a href="profile.php?userID=<?php echo $all["userID"]; ?>"><img src="<?php echo file_exists('./files/images/avatars/' . $all['userID'] . '.jpg') ? ('./files/images/avatars/' . $all['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" alt="<?php echo $currentuser['displayName'] ?>" class="avatar_box_status"></a>
                                            </div>
                                            <div class="ml-2" style="padding:8px;">
                                                <a href="profile.php?userID=<?php echo $all["userID"]; ?>"><div class="h6 m-0"><?php echo $all["displayName"] ?></div></a>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>