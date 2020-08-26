<?php
require_once 'init.php';
if (!$currentuser) {
    header('Location: index.php');
    exit();
}

$commentId = $_POST['comment_id'];
$totalLikes = "No ";
$likeQuery = "SELECT sum(like_unlike) AS likesCount FROM teamx_like_unlike WHERE comment_id=".$commentId;
$resultLikeQuery = mysqli_query($conn,$likeQuery);
$fetchLikes = mysqli_fetch_array($resultLikeQuery,MYSQLI_ASSOC);
if(isset($fetchLikes['likesCount'])) {
    $totalLikes = $fetchLikes['likesCount'];
}

echo $totalLikes;
?>