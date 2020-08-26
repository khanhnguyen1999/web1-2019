<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Đồ án LTWEB1 - Mạng Xã Hội" content="">
    <meta name="TeamX" content="TeamX">
    <link rel="icon" href="favicon.ico">
    
    <title>TeamX - Mạng Xã Hội Việt</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="css/mybootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="css/navbar-top-fixed.css" rel="stylesheet">

    <!-- Datepicker -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Time ago -->
    <script src="js/jquery.timeago.js" type="text/javascript"></script>
    <script src="js/test_helpers.js" type="text/javascript"></script>

  </head>

  <body>
    <nav class="navbar navbar-light bg-light d-md-none d-none d-lg-block d-xl-block position-fixed" style="width:100%;height:58px;padding:.25rem 1rem;"></nav> 
    <div class="container gedf-wrapper">
      <!-- Sticky top navbar -->
      <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">
              <img src="files/images/header/teamX-logo.svg" width="30" height="30" class="d-inline-block align-top" alt="">  
              TeamX
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <?php if ($currentuser): ?>
            <nav class="navbar navbar-light bg-light d-md-none d-none d-lg-block d-xl-block" style="padding-left:5.5rem"></nav>
            <form class="d-flex justify-content-center">
              <input class="form-control mr-sm-2 ts_input" name="search-header" type="text" placeholder="Tìm kiếm" aria-label="Search">
            </form>
            <!-- Notify-->
            <?php include 'notify_nav.php'; ?>  
            <!--END Notify-->
              <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="<?php echo file_exists('./files/images/avatars/' . $currentuser['userID'] . '.jpg') ? ('./files/images/avatars/' . $currentuser['userID'] . '.jpg') : ('./files/images/avatars/0.jpg') ?>" alt="<?php echo $currentuser['displayName'] ?>" class="avatar"><b><?php echo " " . $currentuser['displayName'] ?></b>
                      <span class="caret"></span>  
                      </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                          <a class="dropdown-item" href="profile.php?userID=<?php echo $currentuser['userID']; ?>">Trang của tôi</a>
                          <div class="dropdown-divider"></div>  
                          <a class="dropdown-item" href="update-info.php">Đổi thông tin cá nhân</a>
                          <a class="dropdown-item" href="change-password.php">Đổi mật khẩu</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="logout.php">Đăng xuất</a>
                        </div>
                    </li>
          <?php endif; ?> 
              </ul>
          <?php if (!$currentuser): ?>
              <ul class="navbar-nav ml-auto top_nav_link">
                <li class="nav-item <?php echo $page == 'register' ? 'active' : '' ?>"><a class="nav-link" href="register.php"><i class="fa fa-user fa-fw"></i>&nbsp; Đăng ký</a></li>
                <li class="nav-item <?php echo $page == 'login' ? 'active' : '' ?>"><a class="nav-link" href="login.php"><i class="fa fa-sign-in fa-fw"></i>&nbsp; Đăng nhập</a></li>
              </ul>
          <?php endif; ?>
          </div>
      </nav>
    <!--END Sticky top navbar --> 