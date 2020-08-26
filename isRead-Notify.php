<?php 
  require_once 'init.php';
  if (!$currentuser) {
    header('Location: index.php');
    exit();
  }
?>

<?php if (isset($_POST['fromUserID']) && isset($_POST['idNotify'])): ?>
  <?php
    $idNotify = $_POST['idNotify'];
    
    isReadNotify($idNotify);

    header('Location: profile.php?userID=' . $_POST['fromUserID']);
	?>
<?php else: ?>
  <?php header('Location: index.php') ?>
<?php endif; ?>