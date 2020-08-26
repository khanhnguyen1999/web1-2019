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
    sendFriendRequest($currentuser['userID'], $profile['userID']);

    if ($_POST['notify'] == 1 ){
      sendNewNotiFy_Friend(1, $profile['userID'], $currentuser['userID']);
      SendNofifyByEmail($profile, $currentuser);
    }
    if ($_POST['notify'] == 2 ){
      delNewNotiFy_Friend(1, $currentuser['userID'], $profile['userID']);
      sendNewNotiFy_NotiFy(21, $profile['userID'], $currentuser['userID']);
    }

    header('Location: profile.php?userID=' . $_POST['userID']);
	?>
<?php else: ?>
  <?php header('Location: index.php') ?>
<?php endif; ?>