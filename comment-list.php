<?php
require_once 'init.php';
if (!$currentuser) {
    header('Location: index.php');
    exit();
}

mysqli_set_charset($conn, 'utf8mb4');

$statusID = isset($_POST['status_id']) ? (int)$_POST['status_id'] : "";
$sql = "SELECT teamx_comment.*,teamx_user.*,teamx_like_unlike.like_unlike FROM teamx_comment LEFT JOIN teamx_user ON teamx_comment.userID = teamx_user.userID LEFT JOIN teamx_like_unlike ON teamx_comment.comment_id = teamx_like_unlike.comment_id WHERE statusID = " . $statusID . " ORDER BY parent_comment_id DESC, comment_id DESC";

$result = mysqli_query($conn, $sql);
$record_set = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($record_set, $row);
}
mysqli_free_result($result);

mysqli_close($conn);
echo json_encode($record_set);
?>