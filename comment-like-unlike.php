<?php
require_once 'init.php';
if (!$currentuser) {
    header('Location: index.php');
    exit();
}


$userID = $_POST['userID'];;
$commentId = $_POST['comment_id'];
$likeOrUnlike = 0;
if($_POST['like_unlike'] == 1)
{
$likeOrUnlike = $_POST['like_unlike'];
}

$sql = "SELECT * FROM teamx_like_unlike WHERE comment_id=" . $commentId . " and userID=" . $userID;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (! empty($row)) 
{
    $query = "UPDATE teamx_like_unlike SET like_unlike = " . $likeOrUnlike . " WHERE  comment_id=" . $commentId . " and userID=" . $userID;
} else
{
    $query = "INSERT INTO teamx_like_unlike(userID,comment_id,like_unlike) VALUES ('" . $userID . "','" . $commentId . "','" . $likeOrUnlike . "')";
}
mysqli_query($conn, $query);

$totalLikes = "No ";
$likeQuery = "SELECT sum(like_unlike) AS likesCount FROM teamx_like_unlike WHERE comment_id=".$commentId;
$resultLikeQuery = mysqli_query($conn,$likeQuery);
$fetchLikes = mysqli_fetch_array($resultLikeQuery,MYSQLI_ASSOC);
if(isset($fetchLikes['likesCount'])) {
    $totalLikes = $fetchLikes['likesCount'];
}

echo $totalLikes;
?>