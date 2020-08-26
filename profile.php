<?php 
  require_once 'init.php';
  
  $_SESSION['userIdProfile'] = $_GET['userID'];
  $userID = $_GET['userID'];
  $profile = findUserById($userID);

  $isFollowing = getFriendShip($currentuser['userID'], $profile['userID']);
  $isFollower = getFriendShip($profile['userID'], $currentuser['userID']);

  $getUserStatus = getUserStatus($profile['userID']);
  $getImgUser = getImgUser($profile['userID']);
  $getFriendShipUserID = getFriendShipUserID($profile['userID']);

  $count_friend = 0;
  foreach ($getFriendShipUserID as $frs)
  { 
    $chk_1 = getFriendShip($frs['userID1'], $frs['userID2']);
    $chk_2 = getFriendShip($frs['userID2'], $frs['userID1']);

    if ($chk_1 && $chk_2)
    { 
        $count_friend++;
    }
  }
  $yourFriends = yourFriends($profile['userID']);?>
<?php include 'header.php'; ?>
<?php if ($currentuser): ?>
        <div class="row">
            <!-- Menu Left /////-->
            <?php include 'menu-left.php'; ?>

            <!-- Panel Avarta /////-->
            <div class="col-md-3-profile-col-avarta d-none d-md-block" id="fixed_container">
                <div class="position-fixed" id="fixed_container_position">
                    <div class="card_profile gedf-card">
                        <div class="card-body-profile-avatar">
                            <div class="thumbnail_profile img-responsive">
                                <?php if ($currentuser['userID'] == $profile['userID']): ?>
                                    <div class="box19">
                                        <img src="<?php echo file_exists('./files/images/avatars/' . $profile['userID'] . '.jpg') ? ('./files/images/avatars/' . $profile['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" class="img-fluid img-thumbnail_profile" alt="<?php echo $profile['displayName'] ?>">
                                        <div class="box-content">
                                            <h3 class="title">Cập nhật</h3>
                                            <ul class="icon">
                                                <form action="update-info.php" method="POST" enctype="multipart/form-data" id="change_avatar">
                                                    <li>
                                                        <input type="file" class="form-control" id="avatar" name="avatar" style="display:none" onchange="form.submit()">
                                                        <a id="upload_link" href="javascript:$('#change_avatar').submit();" ><i class="fa fa-camera"></i></a>
                                                    </li>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo file_exists('./files/images/avatars/' . $profile['userID'] . '.jpg') ? ('./files/images/avatars/' . $profile['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" class="img-fluid img-thumbnail_profile" alt="<?php echo $profile['displayName'] ?>">
                                <?php endif ?>
                                <aside>
                                    <?php if ($currentuser['userID'] != $profile['userID']): ?>
                                        <button type="button" id="addClass_Chat" class="btn btn-primary btn-sm btn-block flat_button" onclick="focusTextareaChat()">Gửi tin nhắn</button>
                                        <?php if (!$isFollowing && !$isFollower): ?>
                                            <form method="POST" action="add-friend.php" class="btn_profile_avatar">
                                                <input type="hidden" name="userID" value="<?php echo $_GET['userID']; ?>">
                                                <input type="hidden" name="notify" value="1">
                                                <button type="submit" class="btn btn-primary btn-sm flat_button float-left" style="width:182px">Thêm vào bạn bè</button>
                                            </form>
                                        <?php elseif ($isFollowing && !$isFollower): ?>
                                            <form method="POST" action="remove-friend-request.php">
                                                <div class="dropdown profile-avt-fr">
                                                    <a><button type="button" class="btn btn-second btn-sm flat_button float-left dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:182px;cursor:default;">Đã gửi lời mời kết bạn
                                                    <span class="caret"></span></button></a>
                                                    <div class="dropdown-menu dropdown-menu-fr" aria-labelledby="dropdownMenuButton">
                                                        <input type="hidden" name="userID" value="<?php echo $_GET['userID']; ?>">
                                                        <input type="hidden" name="notify" value="3">    
                                                        <button type="submit" class="dropdown-item" style="cursor:pointer">Hủy lời mời</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php elseif (!$isFollowing && $isFollower): ?>
                                            <div class="dropdown profile-avt-fr">
                                                    <a><button type="button" class="btn btn-second btn-sm flat_button float-left dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:182px;cursor:default;">Trả lời lời mời kết bạn
                                                    <span class="caret"></span></button></a>
                                                    <div class="dropdown-menu dropdown-menu-fr" aria-labelledby="dropdownMenuButton">
                                                        <form method="POST" action="add-friend.php">
                                                            <input type="hidden" name="userID" value="<?php echo $_GET['userID']; ?>">
                                                            <input type="hidden" name="notify" value="2">    
                                                            <button type="submit" class="dropdown-item" style="cursor:pointer">Xác nhận</button>
                                                        </form>
                                                        <form method="POST" action="remove-friend-request.php">
                                                            <input type="hidden" name="userID" value="<?php echo $_GET['userID']; ?>">
                                                            <input type="hidden" name="notify" value="3">        
                                                            <button type="submit" class="dropdown-item" style="cursor:pointer">Xóa yêu cầu</button>
                                                        </form>
                                                    </div>
                                            </div>
                                        <?php elseif ($isFollowing && $isFollower): ?>
                                            <form method="POST" action="remove-friend-request.php">
                                                <div class="dropdown profile-avt-fr">
                                                    <a><button type="button" class="btn btn-second btn-sm flat_button float-left dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:182px;cursor:default;">Đã là bạn bè
                                                    <span class="caret"></span></button></a>
                                                    <div class="dropdown-menu dropdown-menu-fr" aria-labelledby="dropdownMenuButton">
                                                        <input type="hidden" name="userID" value="<?php echo $_GET['userID']; ?>">
                                                        <input type="hidden" name="notify" value="3">        
                                                        <button type="submit" class="dropdown-item" style="cursor:pointer">Hủy kết bạn</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php endif ?>
                                        <button type="button" class="btn btn-second btn-sm flat_button float-right profile_extra_actions_btn"></button>
                                    <?php else: ?>
                                        <a role="button" href="update-info.php" class="btn btn-primary btn-sm btn-block flat_button">Thay đổi thông tin cá nhân</a>
                                    <?php endif ?>
                                </aside>
                            </div>
                        </div>
                    </div>
                    <div class="card gedf-card" style="height:340px;">
                        <div class="card-body" style="padding:0;">
                            <div class="showF" style="display:flow-root;">
                                <h5 class="card-title" style="float:left;">Bạn bè</h5>
                                <a href="#" style="float:right;">Tìm bạn bè</a>
                            </div>
                            <div class="row khanh pt-0">
                                <?php foreach($getFriendShipUserID as $gets):?>
                                    <?php $chk_1 = getFriendShip($gets['userID1'], $gets['userID2']);
                                    $chk_2 = getFriendShip($gets['userID2'], $gets['userID1']); ?>
                                    <?php if($chk_1 && $chk_2):?>
                                        <div class="col-md-6" style="text-align:center;">
                                            <img src="<?php $user = findUserById($gets['userID2']); echo file_exists('./files/images/avatars/' . $user['userID'] . '.jpg') ? ('./files/images/avatars/' . $user['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" class="img-fluid img-thumbnail_profile" alt="<?php echo $user['displayName'] ?>">
                                            <a href="profile.php?userID=<?php $user = findUserById($gets['userID2']); echo $user['userID']; ?>"><div class="h6 m-0"><?php $user = findUserById($gets['userID2']); echo $user["displayName"] ?></div></a>
                                        </div>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- Box Panel Avarta /////-->

        <!-- Box Profile Main /////-->
            <!-- Box User Info /////-->
            <div class="col-md-6 gedf-main">
                <div class="card_profile gedf-card">
                    <div class="card-body-profile-info">
                        <h4 class="card-title"><?php echo $profile['displayName'] ?></h4>
                        <hr>
                        <div class="clear_fix profile_info_row ">
                            <div class="card-text mb-2 text-muted float-left label_profile_user">Ngày sinh nhật:</div>
                            <div class="labeled_profile_user"><?php $date=date_create($profile['ngaysinh']); echo date_format($date, 'd \t\h\á\n\g m \n\ă\m Y') ?></div>
                        </div>
                        <div class="clear_fix profile_info_row ">
                            <div class="card-text mb-2 text-muted float-left label_profile_user">Email:</div>
                            <div class="labeled_profile_user"><?php echo $profile['email'] ?></div>
                        </div>
                        <div class="clear_fix profile_info_row ">
                            <div class="card-text mb-2 text-muted float-left label_profile_user">Số điện thoại:</div>
                            <div class="labeled_profile_user"><?php echo $profile['phone'] ?></div>
                        </div>
                    </div>
                    <div class="counts_module">
                        <div class="page_counter">
                            <div class="count"><?php echo count($getUserStatus) ?></div>
                            <div class="label">Bài viết</div>
                        </div>
                        <div class="page_counter">
                            <div class="count"><?php echo $count_friend ?></div>
                            <div class="label">Bạn bè</div>
                        </div>
                        <div class="page_counter">
                            <div class="count"><?php echo count($getImgUser) ?></div>
                            <div class="label">Hình ảnh</div>
                        </div>
                    </div>
                </div>

                <!-- Box Post Status /////-->
                <?php if ($currentuser['userID'] == $profile['userID']): ?>
                    <?php include 'box-post-status.php'; ?>
                <?php else: ?>
                    <?php if (!$isFollowing && !$isFollower): ?>
                        <div class="card card-p-0 gedf-card" id="files">
                                <div class="card-header text-muted">
                                    <?php echo "BẠN CÓ BIẾT "; echo mb_strtoupper($profile["displayName"]); echo " KHÔNG?"; ?>
                                </div>
                                <div class="card-body">
                                    <div class="card-text float-left">Hãy gửi lời mời kết bạn cho <?php echo $profile["displayName"]; ?>.</div>
                                    <form method="POST" action="add-friend.php" class="btn_profile_avatar">
                                        <input type="hidden" name="userID" value="<?php echo $_GET['userID']; ?>">
                                        <input type="hidden" name="notify" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm float-right">Thêm bạn bè</button>
                                    </form>
                                </div>
                        </div>
                    <?php elseif ($isFollowing && $isFollower): ?>                                        
                        <?php include 'box-post-status.php'; ?>
                    <?php endif ?>
                <?php endif ?>
                <!-- End Box Post Status /////-->

                <!--- \\\\\\\Load Status Profile-->
                <div id="files"></div>
                <div id="load_status"></div>
                <div id="load_status_message"></div>
                <!-- Load Status Profile/////-->

            </div>
        <!--END Box Profile Main /////-->
        </div>
    </div>

    <!--Chat Popup /////-->
        <div class="popup-box chat-popup" id="qnimate">
    		<div class="popup-head">
				<div class="popup-head-left pull-left"><a href="./profile.php?userID=<?php echo $profile['userID']?>"><img src="<?php echo file_exists('./files/images/avatars/' . $profile['userID'] . '.jpg') ? ('./files/images/avatars/' . $profile['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" alt="<?php echo $profile['displayName'] ?>"></a>&nbsp; <a href="./profile.php?userID=<?php echo $profile['userID']?>" style="color:#1c1e21;"><?php echo $profile['displayName'] ?></a></div>
				<div class="popup-head-right pull-right">
				    <div class="btn-group">
    					<button class="chat-header-button" data-toggle="dropdown" type="button" aria-expanded="false">
                            <i class="fa fa-cog fa-lg" style="color:#cccccc"></i> 
                        </button>
						<ul role="menu" class="dropdown-menu pull-right">
                            <li><a href="#">Chặn người này</a></li>
                            <li>
                                <form action="remove-chat.php" method="POST" enctype="multipart/form-data" id="frm_rm_Chat" class="frm_rm_Chat">
                                    <input type="hidden" name="fromUserID" id="fromUserID" value="<?php echo $currentuser['userID']; ?>">
                                    <input type="hidden" name="toUserID" id="toUserID" value="<?php echo $profile['userID']; ?>">           
                                    <a href="javascript:$('#frm_rm_Chat').submit();">Xóa tin nhắn</a>
                                </form> 
                            </li>
							<li><a href="#">Tắt thông báo</a></li>
						</ul>
					</div>
						<button data-widget="remove" id="removeClass_Chat" class="chat-header-button pull-right" type="button" style="color:#cccccc"><i class="fa fa-times fa-lg"></i></button>
                </div>
            </div>
            
			<div class="popup-messages" id="popup-messages">
			    <div class="direct-chat-messages" id="direct-chat-messages"></div>
            </div>
            
			<div class="popup-messages-footer">
                <form action="" method="POST"> 
                    <input type="hidden" name="fromUserID" id="fromUserID" value="<?php echo $currentuser['userID']; ?>">
                    <input type="hidden" name="toUserID" id="toUserID" value="<?php echo $profile['userID']; ?>">
                    <textarea id="message_chat" name="message_chat" class="form-control-chat" placeholder="Nhập tin nhắn..."></textarea>
                </form>
                <div class="btn-footer">
                    <button class="bg_none pull-left"><i class="fa fa-file-image-o"></i> </button>
                    <button class="bg_none pull-left"><i class="fa fa-smile-o"></i> </button>
                    <button class="bg_none pull-left"><i class="fa fa-gamepad"></i> </button>
                    <button class="bg_none pull-left"><i class="fa fa-paperclip"></i> </button>
                    <button class="bg_none pull-left"><i class="fa fa-camera"></i> </button>
                    <button class="bg_none pull-right"><i class="fa fa-thumbs-up"></i> </button>
                </div>
			</div>
        </div>
    <!--Chat Popup /////-->
    
<?php else: ?>
    <br>
    <div class="alert alert-danger text-center" role="alert">
		Bạn cần đăng nhập để xem profile!
	</div>
<?php endif ?>

<?php include 'footer.php'; ?>