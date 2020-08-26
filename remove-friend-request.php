<?php 
  require_once 'init.php';
  if (!$currentuser) {
    header('Location: index.php');
    exit();
  }
?>

<?php if (isset($_POST['userID'])): ?>
  <?php
    $userID = $_POST['userID'];
    $profile = findUserById($userID);
    removeFriendRequest($currentuser['userID'], $profile['userID']);

    if ($_POST['notify'] == 3 ){
      delNewNotiFy_Friend(1, $currentuser['userID'], $profile['userID']);
      delNewNotiFy_Friend(1, $profile['userID'], $currentuser['userID']);
    }

    header('Location: profile.php?userID=' . $_POST['userID']);
	?>
<?php else: ?>
  <?php header('Location: index.php') ?>
<?php endif; ?>