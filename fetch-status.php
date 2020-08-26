<?php
require_once 'init.php';
if (!$currentuser) {
    header('Location: index.php');
    exit();
}

$isFollowing = getFriendShip($currentuser['userID'], $userprofile['userID']);
$isFollower = getFriendShip($userprofile['userID'], $currentuser['userID']);

if(isset($_POST["limit"], $_POST["start"]))
{
    mysqli_set_charset($conn, 'utf8mb4');
    if($currentuser && !$userprofile)
    {
        $query = "SELECT p.*, u.displayName FROM teamx_user_status As p JOIN teamx_user AS u ON u.userID=p.userID WHERE privacy='Public' OR (u.userID=".$currentuser["userID"].") ORDER BY statusID DESC LIMIT ".$_POST["start"].", ".$_POST["limit"]."";
    }
    if($currentuser && $userprofile && ($currentuser == $userprofile))
    {
        $query = "SELECT p.*, u.displayName FROM teamx_user_status As p JOIN teamx_user AS u ON u.userID=p.userID WHERE u.userID=".$currentuser["userID"]." ORDER BY statusID DESC LIMIT ".$_POST["start"].", ".$_POST["limit"]."";
    }
    if($currentuser && $userprofile && ($currentuser != $userprofile))
    {
        if($isFollowing && $isFollower)
        {
            $query = "SELECT p.*, u.displayName FROM teamx_user_status As p JOIN teamx_user AS u ON u.userID=p.userID WHERE (u.userID=".$userprofile["userID"]." AND privacy='Public') OR (u.userID=".$userprofile["userID"]." AND privacy='Friends') ORDER BY statusID DESC LIMIT ".$_POST["start"].", ".$_POST["limit"]."";
        }
        else
        {
            $query = "SELECT p.*, u.displayName FROM teamx_user_status As p JOIN teamx_user AS u ON u.userID=p.userID WHERE u.userID=".$userprofile["userID"]." AND privacy='Public' ORDER BY statusID DESC LIMIT ".$_POST["start"].", ".$_POST["limit"]."";
        }
    }

    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result))
    {
        if($row["privacy"] == "Public") {				
            $_privacy = "fa-globe";}
        elseif($row["privacy"] == "Friends") {				
            $_privacy = "fa-group";}
        elseif($row["privacy"] == "Just me") {				
            $_privacy = "fa-user";}
            
        echo '
            <script src="js/fetch-status.js?v=1.1"></script>
            <div class="card card-p-0 gedf-card mx-auto my-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <a href="profile.php?userID='.$row["userID"].'"><img class="rounded-circle" width="70" src="'.(file_exists('./files/images/avatars/' . $row["userID"] . '.jpg') ? ('./files/images/avatars/' . $row["userID"] . '.jpg') : ('./files/images/avatars/0.jpg')).'" alt="Avatar" class="avatar_news"></a>
                            </div>
                            <div class="ml-2">
                                <a href="profile.php?userID='.$row["userID"].'"><div class="h5 m-0">'.$row["displayName"].'</div></a>
                                <div class="text-muted h7 mb-2"><i class="fa fa-clock-o"></i> <time class="timeago" id="timeago" datetime="'.$row["createdAt"].'">'.$row["createdAt"].'</time> · <i class="fa '.$_privacy.'"></i></div>
                            </div>
                        </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                    <div class="h6 dropdown-header">Configuration</div>
                                    <a class="dropdown-item" href="#">Save</a>
                                    <a class="dropdown-item" href="#">Hide</a>
                                    <a class="dropdown-item" href="#">Report</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">';
                    $_content = chkStatusisNull($row["statusID"]);
                    if($_content["content"] != "NULL")
                    {
        echo            '<div class="wall_post_text content_readMore">
                            <p>'.$row["content"].'</p>
                        </div>';
                    }
                    if(chkStatusIgm($row['statusID']) != null)
                    {

        echo           '<div class="page_post_sized_thumbs clear_fix img-fluid" style="width:710px;">';

                        $statement = $db->prepare("SELECT * FROM teamx_status_images WHERE statusID= ? ORDER BY imageID DESC");
                        $statement->execute(array($row['statusID']));

                        $output = '<div class="row-status justify-content-md-center">';

                        if($statement->execute())
                        {
                            $result_img = $statement->fetchAll();

                            if(count($result_img) == 1){
                                $grid_col = '<div class="col-sm" style="margin-bottom:5px;padding-right:0px;padding-left:0px;">';}
                            elseif(count($result_img) == 2){
                                $grid_col = '<div class="col-md-6" style="margin-bottom:5px;padding-right:0px;padding-left:0px;">';}
                            elseif(count($result_img) == 3){
                                $grid_col = '<div class="col-md-6" sstyle="margin-bottom:5px;padding-right:0px;padding-left:0px;">';}
                            elseif(count($result_img) == 4){
                                $grid_col = '<div class="col-md-6" style="margin-bottom:5px;padding-right:0px;padding-left:0px;">';}
                            else{
                                $grid_col = '<div class="col-md-4" style="margin-bottom:5px;padding-right:0px;padding-left:0px;">';}

                            foreach($result_img as $row_img)
                            {
                                $output .= '
                                '.$grid_col.'
                                    <img class="img-fluid img-thumbnail rounded mx-auto d-block" src="data:image;base64,'.base64_encode($row_img['image']).'" style="width:100%;display:block;" />
                                </div>
                                ';
                            }
                        }

                        $output .= '</div></div>';

                        echo $output;
                    }
        echo    '</div>
                <div class="card-footer" style="background: #FFF;">
                    <div class="btn-group col">
                        <form class="frmLike">
                            <input type="hidden" name="statusID" id="statusID" value="'.$row["statusID"].'">';
                            $resultlike = mysqli_query($conn, "SELECT * FROM teamx_user_likes WHERE userID=".$currentuser['userID']." AND statusID=".$row['statusID']."");
                            $countlike = mysqli_query($conn, "SELECT * FROM teamx_user_status WHERE statusID=".$row['statusID']."");
                            $row_countlike = mysqli_fetch_array($countlike);
                            $n = $row_countlike['likes'];
                            if (mysqli_num_rows($resultlike) == 1 ) {
        echo               '<button type="submit" onclick="btnUnLike(this)" class="fa fa-heart btn btn-outline-danger btn-sm btn-block" style="border:none;"> Like</button>';
                            } else {
        echo               '<button type="submit" onclick="btnLike(this)" class="fa fa-heart-o btn btn-outline-danger btn-sm btn-block" style="border:none;"> Like</button>';
                            }
        echo            '</form>
                        <form>
                            <button type="button" onclick="focusCommentBox('.$row["statusID"].')" class="fa fa-comment btn btn-outline-info btn-sm btn-block" style="border:none;"> Comment</button>
                        </form>
                        <form method="POST" action="#">
                            <input type="hidden" name="share_statusID" value="'.$row["statusID"].'">
                            <button type="button" class="fa fa-mail-forward btn btn-outline-success btn-sm btn-block" style="border:none;"> Share</button>
                        </form>
                        <div id="1x" class="text-muted" style="margin-left:auto!important;">'.$n.' người like điều này!</div>

                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between">
                            <div class="mr-2">
                                <a href="profile.php?userID='.$currentuser['userID'].'"><img src="'.(file_exists('./files/images/avatars/' . $currentuser['userID'] . '.jpg') ? ('./files/images/avatars/' . $currentuser['userID'] . '.jpg') : ('./files/images/avatars/0.jpg')).'" alt="'.$currentuser['displayName'].'" class="avatar_box_status"></a>
                            </div>
                            <div class="ml-2">
                                <form id="frm-comment-'.$row["statusID"].'">
                                    <div>
                                        <input type="hidden" name="status_id" value="'.$row["statusID"].'"
                                            placeholder="statusID" />
                                        <input type="hidden" name="comment_id" id="commentId-'.$row["statusID"].'"
                                            placeholder="CommentID" /> <input class="input-field"
                                            type="hidden" name="userID" id="name-'.$row["statusID"].'" value="'.$currentuser['userID'].'" placeholder="userID" />
                                    </div>
                                    <div>
                                        <textarea type="text" name="comment"
                                            id="comment-'.$row["statusID"].'" class="form-control-comment" rows="1" cols="60%" placeholder="Viết bình luận..."></textarea>
                                        <span></span> 
                                    </div>
                                </form>
                            </div>
                            <div class="ml-2">
                                <input type="button" class="btn btn-primary" id="submitButton-'.$row["statusID"].'"
                                value="Đăng" data-field-id="'.$row["statusID"].'"/>                             
                            </div>
                        </div>
                    </div>
                    <div id="output-'.$row["statusID"].'"></div>

                    <script>

                    $("#submitButton-'.$row["statusID"].'").attr("disabled", true);
                    $("textarea[id=comment-'.$row["statusID"].']").keyup(function(){
                    if($.trim($(this).val()).length  <= 0)$("#submitButton-'.$row["statusID"].'").attr("disabled", true)
                    else $("#submitButton-'.$row["statusID"].'").attr("disabled", false)
                    });

                    var totalLikes = 0;
                    var totalUnlikes = 0;
                
                    function postReply(commentId, commentBoxId) {
                        $("#commentId-"+commentBoxId).val(commentId);
                        $("#comment-"+commentBoxId).focus();
                    }

                    $("#submitButton-'.$row["statusID"].'").click(function () {
                        $("#submitButton-'.$row["statusID"].'").attr("disabled", true);

                        var field_id = $(this).attr("data-field-id");
                        $("#comment-message").css("display", "none");
                        var str = $("#frm-comment-'.$row["statusID"].'").serialize();

                        $.ajax({
                            url: "comment-add.php",
                            data: str,
                            type: "post",
                            success: function (response)
                            {
                                var result = eval("(" + response + ")");
                                if (response)
                                {
                                    $("#comment-message").css("display", "inline-block");
                                    $("#comment-'.$row["statusID"].'").val("");
                                    $("#commentId-'.$row["statusID"].'").val("");
                                    listComment_'.$row["statusID"].'();
                                } else
                                {
                                    alert("Failed to add comments !");
                                    return false;
                                }
                            }
                        });
                    });

                    $(document).ready(function () {
                        listComment_'.$row["statusID"].'();
                    });

                    function listComment_'.$row["statusID"].'() {
                        $.post("comment-list.php", {status_id : '.$row["statusID"].'},
                                function (data) {
                                    var data = JSON.parse(data);

                                    var comments = "";
                                    var replies = "";
                                    var item = "";
                                    var parent = -1;
                                    var results = new Array();

                                    var list = $("<ul class='."'outer-comment'".'>");
                                    var item = $("<li>").html(comments);

                                    jQuery(document).ready(function($){$(".timeago").timeago();});

                                    for (var i = 0; (i < data.length); i++)
                                    {
                                        var commentId = data[i]['."'comment_id'".'];
                                        var userId = data[i]['."'userID'".'];
                                        var haveAvatar = data[i]['."'haveAvatar'".'];
                                        var displayName = data[i]['."'displayName'".'];

                                        parent = data[i]['."'parent_comment_id'".'];

                                        var obj = getLikesUnlikes(commentId);

                                        if (parent == "0")
                                        {
                                            if(data[i]['."'like_unlike'".'] >= 1) 
                                            {
                                                like_icon = "<img src='."'./files/images/comment/like.png'".'  id='."'unlike_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".' onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",-1,".$currentuser["userID"].")'".' />";
                                                like_icon += "<img style='."'display:none;'".' src='."'./files/images/comment/unlike.png'".' id='."'like_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".' onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",1,".$currentuser["userID"].")'".' />";
                                            }   
                                            else
                                            {
                                                like_icon = "<img style='."'display:none;'".' src='."'./files/images/comment/like.png'".'  id='."'unlike_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".'  onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",-1,".$currentuser["userID"].")'".' />";
                                                like_icon += "<img src='."'./files/images/comment/unlike.png'".' id='."'like_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".' onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",1,".$currentuser["userID"].")'".' />";
                                                
                                            }
                                            
                                            comments = "\
                                                <div class='."'comment-row'".'>\
                                                    <div class='."'d-flex justify-content-between align-items-center'".'>\
                                                        <div class='."'d-flex justify-content-between'".'>\
                                                            <div class='."'mr-2'".'>\
                                                                <a href='."'profile.php?userID=".'" + userId + "'."'".'><img src='.("./files/images/avatars/".'" + haveAvatar + "'.".jpg").' alt=" + userId + " class='."'avatar_box_status'".'></a>\
                                                            </div>\
                                                            <div class='."'ml-2'".'>\
                                                                <div class='."'comment-text'".'><a href='."'profile.php?userID=".'" + userId + "'."'".'><span class='."'posted-by'".'>" + data[i]['."'displayName'".'] + "</span></a> " + data[i]['."'comment'".'] + "</div>\
                                                                <div class='."'post-action'".'>\ " + like_icon + "&nbsp;\
                                                                    <span id='."'likes_".'" + commentId + "'."'".' class='."'btn-reply'".'> " + totalLikes + " likes</span>\
                                                                    <span> · </span>\
                                                                    <a class='."'btn-reply'".' onClick='."'postReply(".'" + commentId + "'."".','.$row["statusID"].''.")'".'>Trả lời</a>\
                                                                    <span> · </span>\
                                                                    <span class='."'commet-row-label text-muted'".'></span>\
                                                                    <time class='."'timeago text-muted'".' id='."'timeago'".' datetime='."'".'" + data[i]['."'date'".'] + "'."'".'><span class='."'posted-at'".'>" + data[i]['."'date'".'] + "</span></time>\
                                                                </div>\
                                                            </div>\
                                                        </div>\
                                                    </div>\
                                                </div>";

                                            var item = $("<li>").html(comments);
                                            list.append(item);
                                            var reply_list = $('."'<ul>'".');
                                            item.append(reply_list);
                                            listReplies(commentId, data, reply_list);
                                        }
                                    }
                                    $("#output-'.$row["statusID"].'").html(list);
                                });
                    }

                    function listReplies(commentId, data, list) {

                        for (var i = 0; (i < data.length); i++)
                        {
                            var userId = data[i]['."'userID'".'];
                            var displayName = data[i]['."'displayName'".'];

                            var obj = getLikesUnlikes(data[i].comment_id);
                            if (commentId == data[i].parent_comment_id)
                            {
                                if(data[i]['."'like_unlike'".'] >= 1) 
                                {
                                    like_icon = "<img src='."'./files/images/comment/like.png'".'  id='."'unlike_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".' onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",-1,".$currentuser["userID"].")'".' />";
                                    like_icon += "<img style='."'display:none;'".' src='."'./files/images/comment/unlike.png'".' id='."'like_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".' onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",1,".$currentuser["userID"].")'".' />";
                                }   
                                else
                                {
                                    like_icon = "<img style='."'display:none;'".' src='."'./files/images/comment/like.png'".'  id='."'unlike_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".'  onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",-1,".$currentuser["userID"].")'".' />";
                                    like_icon += "<img src='."'./files/images/comment/unlike.png'".' id='."'like_".'" + data[i]['."'comment_id'".'] + "'."'".' class='."'like-unlike'".' onClick='."'likeOrDislike(".'" + data[i]['."'comment_id'".'] + "'.",1,".$currentuser["userID"].")'".' />";                                   
                                }
                                var comments = "\
                                                <div class='."'comment-row-child'".'>\
                                                    <a href='."'profile.php?userID=".'" + userId + "'."'".'><img src='.(("./files/images/avatars/".'" + userId + "'.".jpg") ? ("./files/images/avatars/".'" + userId + "'.".jpg") : ("./files/images/avatars/0.jpg")).' alt=" + userId + " class='."'avatar_child_comment'".'></a>\
                                                    <div class='."'comment-text'".'><a href='."'profile.php?userID=".'" + userId + "'."'".'><span class='."'posted-by'".'>" + data[i]['."'displayName'".'] + "</span></a> " + data[i]['."'comment'".'] + "</div>\
                                                    <div class='."'post-action-reply'".'> " + like_icon + "&nbsp;\
                                                        <span id='."'likes_".'" + data[i]['."'comment_id'".'] + "'."'".'> " + totalLikes + " likes </span>\
                                                        <span> · </span>\
                                                        <a class='."'btn-reply'".' onClick='."'postReply(".'" + data[i]['."'comment_id'".'] + "'.")'".'>Trả lời</a>\
                                                        <span> · </span>\
                                                        <span class='."'commet-row-label text-muted'".'></span>\
                                                        <time class='."'timeago text-muted'".' id='."'timeago'".' datetime='."'".'" + data[i]['."'date'".'] + "'."'".'><span class='."'posted-at'".'>" + data[i]['."'date'".'] + "</span></time>\
                                                        </div>\
                                                </div>";

                                var item = $("<li>").html(comments);
                                var reply_list = $('."'<ul>'".');
                                list.append(item);
                                item.append(reply_list);
                                listReplies(data[i].comment_id, data, reply_list);
                            }
                        }
                    }

                    function getLikesUnlikes(commentId)
                    {

                        $.ajax({
                            type: "POST",
                            async: false,
                            url: "get-like-unlike.php",
                            data: {comment_id: commentId},
                            success: function (data)
                            {
                                totalLikes = data;
                            }

                        });

                    }
                                               
                    function likeOrDislike(comment_id,like_unlike,userID)
                    {
                    
                        $.ajax({
                            url: "comment-like-unlike.php",
                            async: false,
                            type: "post",
                            data: {comment_id:comment_id,like_unlike:like_unlike,userID:userID},
                            dataType: "json",
                            success: function (data) {
                                
                                $("#likes_"+comment_id).text(data + " likes");
                                
                                if (like_unlike == 1) { 
                                    $("#like_" + comment_id).css("display", "none");
                                    $("#unlike_" + comment_id).show();
                                }

                                if (like_unlike == -1) {
                                    $("#unlike_" + comment_id).css("display", "none");
                                    $("#like_" + comment_id).show();
                                }
                                
                            },
                            error: function (data) {
                                alert("error : " + JSON.stringify(data));
                            }
                        });
                    }
                    </script>

                </div>
            </div>
            
        ';
    }
}

?>