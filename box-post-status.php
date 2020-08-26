<div class="card gedf-card">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-between">
            <div class="mr-2">
                <a href="profile.php?userID=<?php echo $currentuser['userID']; ?>"><img src="<?php echo file_exists('./files/images/avatars/' . $currentuser['userID'] . '.jpg') ? ('./files/images/avatars/' . $currentuser['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" alt="<?php echo $currentuser['displayName'] ?>" class="avatar_box_status"></a>
            </div>
            <div class="ml-2">
                <?php if ($page == 'index'): ?>
                    <textarea data-toggle="modal" data-target="#PostStatusModal" rows="1" cols="70" id="ssSt_content_status" name="ssSt_content_status" placeholder="<?php echo $currentuser['displayName'] ?> ơi, bạn có gì mới không?"></textarea>
                <?php else: ?>
                <?php if ($currentuser['userID'] == $profile['userID']): ?>
                    <textarea data-toggle="modal" data-target="#PostStatusModal" rows="1" cols="70" id="ssSt_content_status" name="ssSt_content_status" placeholder="<?php echo $currentuser['displayName'] ?> ơi, bạn có gì mới không?"></textarea>
                <?php else: ?>
                    <textarea data-toggle="modal" data-target="#PostStatusModal" rows="1" cols="70" id="ssSt_content_status" name="ssSt_content_status" placeholder="Viết gì đó cho <?php echo $profile['displayName'] ?>..."></textarea>
                <?php endif ?>
                <?php endif ?>
                <!-- Modal Box Post Status-->
                <div class="modal" id="PostStatusModal" tabindex="-1" role="dialog" aria-labelledby="PostStatusModalLabel" aria-hidden="true">
                     <div class="modal-dialog modal-md-tl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <b class="modal-title" id="PostStatusModalLabel">Tạo bài viết</b>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="create-status.php" method="POST" enctype="multipart/form-data" id="sessionStorage">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex justify-content-between">
                                                <div class="mr-2">
                                                    <a href="profile.php?userID=<?php echo $currentuser['userID']; ?>"><img src="<?php echo file_exists('./files/images/avatars/' . $currentuser['userID'] . '.jpg') ? ('./files/images/avatars/' . $currentuser['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" alt="<?php echo $currentuser['displayName'] ?>" class="avatar_box_status"></a>
                                                </div>
                                                <div class="ml-2">
                                                    <?php if ($page == 'index'): ?>
                                                        <textarea class="form-control-x stored" rows="1" cols="53" id="content_status" name="content_status" style="height:.1em;" placeholder="<?php echo $currentuser['displayName'] ?> ơi, bạn có gì mới không?" autofocus></textarea>
                                                    <?php else: ?>
                                                    <?php if ($currentuser['userID'] == $profile['userID']): ?>
                                                        <textarea class="form-control-x stored" rows="1" cols="53" id="content_status" name="content_status" style="height:.1em;" placeholder="<?php echo $currentuser['displayName'] ?> ơi, bạn có gì mới không?" autofocus></textarea>
                                                    <?php else: ?>
                                                        <textarea class="form-control-x stored" rows="1" cols="53" id="content_status" name="content_status" style="height:.1em;" placeholder="Viết gì đó cho <?php echo $profile['displayName'] ?>..." autofocus></textarea>
                                                    <?php endif ?>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <output id="list" name="list"></output>
                                    </div>
                                    <div class="form-group">   
                                        <ul class="list-group list-group-horizontal-xl">
                                            <input type="file" multiple class="form-control-file" id="files" name="files[]" style="display:none"/>
                                            <label for="files" id="image" style="cursor:pointer;color:#2a5885;" class="list-group-item_fix_box-status"><i class="fa fa-camera fa-fw"></i><span>&nbsp; Hình Ảnh</span></label>&nbsp;
                                            <textarea type="text" id="filePath" name="filePath"></textarea>
                                            <a href="#" class="list-group-item_fix_box-status"><i class="fa fa-smile-o fa-fw"></i><span>&nbsp; Cảm Xúc</span></a>&nbsp;
                                            <a href="#" class="list-group-item_fix_box-status"><i class="fa fa-map-marker fa-fw"></i><span>&nbsp; Check in</span></a>&nbsp;
                                            <a href="#" class="list-group-item_fix_box-status"><i class=""></i><span>...</span></a>
                                        </ul>
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-toolbar justify-content-end">                                       
                                            <div class="dropdown btn-group">
                                                <select class="btn btn-link-x dropdown-toggle" id="privacy" name="privacy">
                                                        <option selected value="Public">&#xf0ac; Public</option>
                                                        <option value="Friends">&#xf0c0; Friends</option>
                                                        <option value="Just me">&#xf007; Just me</option>
                                                </select>
                                            </div>
                                            <button type="submit" id ="btn_postStatus" class="btn btn-md btn-primary btn-block text-uppercase">Đăng</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-none d-lg-block">
        <hr>
        <ul class="list-group list-group-horizontal-xl">
            <input type="file" multiple class="form-control-file" id="files" name="files[]" style="display:none"/>
            <label for="files" id="image" style="cursor:pointer;color:#2a5885;" data-toggle="modal" data-target="#PostStatusModal" class="list-group-item_fix_box-status"><i class="fa fa-camera fa-fw"></i><span>&nbsp; Hình Ảnh</span></label>&nbsp;
            <a href="#" class="list-group-item_fix_box-status"><i class="fa fa-smile-o fa-fw"></i><span>&nbsp; Cảm Xúc</span></a>&nbsp;
            <a href="#" class="list-group-item_fix_box-status"><i class="fa fa-map-marker fa-fw"></i><span>&nbsp; Check in</span></a>&nbsp;
            <a href="#" class="list-group-item_fix_box-status"><i class=""></i><span>...</span></a>
        </ul>
    </div>
</div>