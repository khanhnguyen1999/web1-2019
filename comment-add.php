<?php
require_once 'init.php';
if (!$currentuser) {
    header('Location: index.php');
    exit();
}

mysqli_set_charset($conn, 'utf8mb4');

$statusID = isset($_POST['status_id']) ? $_POST['status_id'] : "";
$commentId = isset($_POST['comment_id']) ? $_POST['comment_id'] : "";
$comment = isset($_POST['comment']) ? $_POST['comment'] : "";
$userID = isset($_POST['userID']) ? $_POST['userID'] : "";
// $date = date('Y-m-d H:i:s');

// $sql = "INSERT INTO teamx_comment(parent_comment_id,statusID,userID,comment,date) VALUES ('" . $commentId . "','" . $statusID . "','" . $userID . "','" . $comment . "','" . $date . "')";
$sql = "INSERT INTO teamx_comment(parent_comment_id,statusID,userID,comment) VALUES ('" . $commentId . "','" . $statusID . "','" . $userID . "','" . $comment . "')";

$result = mysqli_query($conn, $sql);

if (! $result) {
    $result = mysqli_error($conn);
}
echo $result;
?>
