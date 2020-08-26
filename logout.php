<?php 
  require_once 'init.php';

 unset($_SESSION['userId']);
 unset($_SESSION['userIdProfile']);
 header('Location: index.php');