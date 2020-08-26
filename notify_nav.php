<?php 
  require_once 'init.php';
  if (!$currentuser) {
    header('Location: index.php');
    exit();
  }
  
$notify_friend = getNewNotiFy_Friend(1, $currentuser['userID']);
$notify_notify_fr = getNewNotiFy_NotiFy(21, $currentuser['userID']);
$notify_mess = getNewNotiFy_Mess(3, $currentuser['userID']);


$count_notify_fr_notisRead = getNewNotiFy_NotiFy_notisRead(21, $currentuser['userID']);
$count_notifyOffr_notisRead = getNewNotiFy_NotiFy_notisRead(1, $currentuser['userID']);
$count_notifyOfMess_notisRead = getNewNotiFy_Mess_notisRead(3, $currentuser['userID']);

?>
            <ul class="nav nav-pills mr-auto d-flex justify-content-center">
                <li class="nav-item nav-item-hnone dropdown">
                  <a class="nav-link <?php echo (count($count_notifyOffr_notisRead) == 0) ? "" : "notification" ?>" href="#" id="navbarDropdown" style="padding:.25rem .7rem;" role="button" data-placement="bottom" title="Lời mời kết bạn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="badge"><?php echo (count($count_notifyOffr_notisRead) == 0) ? "" : count($count_notifyOffr_notisRead); ?></span>
                    <i class="fa fa-user-plus fa-lg"></i>
                  </a>
                    <ul class="dropdown-menu dropdown-menu-notify">
                      <li class="head-menu-notify bg-box-notify-nav">
                        <div class="row_notify_tilte">
                          <div class="col-lg-12 col-sm-12 col-12">
                            <span class="font-weight-bold">Lời mời kết bạn</span>
                            <a href="" class="float-right">Tìm bạn bè</a>
                          </div>
                      </li>

                    <?php if (count($notify_friend) > 0): ?>
                    <?php foreach ($notify_friend as $notify_f): ?>
                      <form action="isRead-Notify.php" method="POST" enctype="multipart/form-data" id="frm_isR_<?php echo $notify_f['id']; ?>">
                        <a class="link_notify_nav" href="javascript:$('#frm_isR_<?php echo $notify_f['id']; ?>').submit();" style="text-decoration:none;">
                            <li class="notification-box <?php echo ($notify_f['isRead'] == 0) ? "bg-gray" : "" ?>">
                            <div class="row_notify">
                                <div class="col-lg-3 col-sm-3 col-3 text-right">
                                <img src="./files/images/avatars/<?php echo $notify_f['fromUserID']; ?>.jpg" class="w-50 rounded-circle">
                                </div>    
                                <div class="col-lg-8 col-sm-8 col-8">
                                <strong class="text-info"><?php echo $notify_f['displayName']; ?></strong>
                                <div>
                                    Đã gửi lời mời kết bạn với bạn.
                                </div>
                                <small class="text-muted"><i class="fa fa-clock-o"></i> <time class="timeago" id="timeago" datetime="<?php echo $notify_f['createdAt']; ?>"><?php echo $notify_f['createdAt']; ?></time></small>
                                </div>    
                            </div>
                            </li>
                          <input type="hidden" name="fromUserID" value="<?php echo $notify_f['fromUserID']; ?>">
                          <input type="hidden" name="idNotify" value="<?php echo $notify_f['id']; ?>">
                        </a>
                      </form> 
                    <?php endforeach ?>
                    <?php else: ?>
                        <a class="link_notify_nav" style="text-decoration:none;">
                            <li class="notification-box">
                            <div class="row_notify"> 
                                <div class="col-lg col-sm col text-center">
                                    Không có lời mời kết bạn nào.
                                </div>
                            </div>
                            </li>
                        </a>    
                    <?php endif ?>

                        <li class="footer-menu-notify bg-box-notify-footer-nav text-center">
                            <a href="" class="font-weight-bold">Xem tất cả</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-hnone dropdown">
                  <a class="nav-link <?php echo (count($count_notifyOfMess_notisRead) == 0) ? "" : "notification" ?>" href="#" id="navbarDropdown" style="padding:.25rem .7rem" role="button" data-placement="bottom" title="Tin nhắn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="badge"><?php echo (count($count_notifyOfMess_notisRead) == 0) ? "" : count($count_notifyOfMess_notisRead); ?></span>
                    <i class="fa fa-envelope fa-lg"></i>
                  </a>
                    <ul class="dropdown-menu dropdown-menu-notify">
                      <li class="head-menu-notify bg-box-notify-nav">
                        <div class="row_notify_tilte">
                          <div class="col-lg-12 col-sm-12 col-12">
                            <span class="font-weight-bold">Tin nhắn đang chờ</span>
                            <a href="" class="float-right">Tin nhắn mới</a>
                          </div>
                      </li>

                      <?php if (count($notify_mess) > 0): ?>
                      <?php foreach ($notify_mess as $notify_m): ?>
                      <form action="isRead-Notify.php" method="POST" enctype="multipart/form-data" id="frm_isR_<?php echo $notify_m['id']; ?>">
                        <a class="link_notify_nav" href="javascript:$('#frm_isR_<?php echo $notify_m['id']; ?>').submit();" style="text-decoration:none;">
                          <li class="notification-box <?php echo ($notify_m['isRead'] == 0) ? "bg-gray" : "" ?>">
                            <div class="row_notify">
                              <div class="col-lg-3 col-sm-3 col-3 text-right">
                                <img src="./files/images/avatars/<?php echo $notify_m['fromUserID']; ?>.jpg" class="w-50 rounded-circle">
                              </div>    
                              <div class="col-lg-8 col-sm-8 col-8">
                                <strong class="text-info"><?php echo $notify_m['displayName']; ?></strong>
                                <div>
                                  Đang nhắn tin cho bạn...
                                </div>
                                <small class="text-muted"><i class="fa fa-clock-o"></i> <time class="timeago" id="timeago" datetime="<?php echo $notify_m['createdAt']; ?>"><?php echo $notify_m['createdAt']; ?></time></small>
                              </div>    
                            </div>
                          </li>
                          <input type="hidden" name="fromUserID" value="<?php echo $notify_m['fromUserID']; ?>">
                          <input type="hidden" name="idNotify" value="<?php echo $notify_m['id']; ?>">
                        </a>
                      </form> 
                      <?php endforeach ?>
                      <?php else: ?>
                        <a class="link_notify_nav" style="text-decoration:none;">
                            <li class="notification-box">
                            <div class="row_notify"> 
                                <div class="col-lg col-sm col text-center">
                                    Không có tin nhắn nào.
                                </div>
                            </div>
                            </li>
                        </a>    
                      <?php endif ?>

                      <li class="footer-menu-notify bg-box-notify-footer-nav text-center" style="margin-bottom:25px;">
                        <a href="" class="float-left">Xem tất cả trong Messenger</a>
                        <a href="" class="float-right">Đánh dấu tất cả là đã đọc</a>
                      </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-hnone dropdown">
                  <a class="nav-link <?php echo (count($count_notify_fr_notisRead) == 0) ? "" : "notification" ?>" href="#" id="navbarDropdown" style="padding:.25rem .7rem" role="button" data-placement="bottom" title="Thông báo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="badge"><?php echo (count($count_notify_fr_notisRead) == 0) ? "" : count($count_notify_fr_notisRead); ?></span>
                    <i class="fa fa-bell fa-lg"></i>
                  </a>
                    <ul class="dropdown-menu dropdown-menu-notify">
                      <li class="head-menu-notify bg-box-notify-nav">
                        <div class="row_notify_tilte">
                          <div class="col-lg-12 col-sm-12 col-12">
                            <span class="font-weight-bold">Thông báo</span>
                            <a href="" class="float-right">Đánh dấu tất cả là đã đọc</a>
                          </div>
                      </li>

                      <?php if (count($notify_notify_fr) > 0): ?>
                      <?php foreach ($notify_notify_fr as $notify_n): ?>
                      <form action="isRead-Notify.php" method="POST" enctype="multipart/form-data" id="frm_isR_<?php echo $notify_n['id']; ?>">
                        <a class="link_notify_nav" href="javascript:$('#frm_isR_<?php echo $notify_n['id']; ?>').submit();" style="text-decoration:none;">
                          <li class="notification-box <?php echo ($notify_n['isRead'] == 0) ? "bg-gray" : "" ?>">
                            <div class="row_notify">
                              <div class="col-lg-3 col-sm-3 col-3 text-right">
                                <img src="./files/images/avatars/<?php echo $notify_n['fromUserID']; ?>.jpg" class="w-50 rounded-circle">
                              </div>    
                              <div class="col-lg-8 col-sm-8 col-8">
                                <strong class="text-info"><?php echo $notify_n['displayName']; ?></strong>
                                <div>
                                  Đã đồng ý kết bạn với bạn.
                                </div>
                                <small class="text-muted"><i class="fa fa-clock-o"></i> <time class="timeago" id="timeago" datetime="<?php echo $notify_n['createdAt']; ?>"><?php echo $notify_n['createdAt']; ?></time></small>
                              </div>    
                            </div>
                          </li>
                          <input type="hidden" name="fromUserID" value="<?php echo $notify_n['fromUserID']; ?>">
                          <input type="hidden" name="idNotify" value="<?php echo $notify_n['id']; ?>">
                        </a>
                      </form>
                      <?php endforeach ?>
                      <?php else: ?>
                        <a class="link_notify_nav" style="text-decoration:none;">
                            <li class="notification-box">
                            <div class="row_notify"> 
                                <div class="col-lg col-sm col text-center">
                                    Không có thông báo nào.
                                </div>
                            </div>
                            </li>
                        </a>    
                      <?php endif ?>
                      <li class="footer-menu-notify bg-box-notify-footer-nav text-center">
                        <a href="" class="font-weight-bold">Xem tất cả</a>
                      </li>
                    </ul>
                </li>
            </ul>