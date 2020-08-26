<?php 
  require_once 'init.php';
  if (!$currentuser) {
    header('Location: index.php');
    exit();
  }
?>

<?php if (isset($_POST['fromUserID']) && isset($_POST['toUserID'])): ?>
  <?php
    $statusID = $_POST['fromUserID'];
    $toUserID = $_POST['toUserID'];

    removeChat($statusID, $toUserID);

    ?>
<?php else: ?>
  <?php header('Location: index.php') ?>
<?php endif; ?>