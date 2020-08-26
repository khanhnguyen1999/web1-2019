<?php 
  require_once 'init.php';
  if (!$currentuser) {
    header('Location: index.php');
    exit();
  }
?>

<?php if (isset($_POST['statusID'])): ?>
  <?php
    $statusID = $_POST['statusID'];

    if (chkLikedStatus($statusID, $currentuser['userID'])) {
        removeLikedStatus($statusID, $currentuser['userID']);
        updateLikeCountStatusRemove($statusID);
        // header('Location: index.php');
    }
    else {
        updateLikeCountStatusAdd($statusID);
        likeStatus($statusID, $currentuser['userID']);
        // header('Location: index.php');
    }

	?>
<?php else: ?>
  <?php header('Location: index.php') ?>
<?php endif; ?>