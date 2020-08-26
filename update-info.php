<?php 
  require_once 'init.php';
   if (!$currentuser) {
  	header('Location: index.php');
  	exit();
  }
?>
<?php include 'header.php'; ?>
<div class="container">
	<div class="row">
		<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
			<div class="card card-signin my-6">
				<div class="card-body">
					<h1 class="card-title text-center fadein_animation">Đổi thông tin cá nhân</h1>
					<?php if (isset($_POST['displayName']) || isset($_POST['phone']) || isset($_POST['ngaysinh']) || isset($_FILES['avatar'])): ?>
						<?php 
							$displayName = $_POST['displayName'];
							$phone = $_POST['phone'];
							$ngaysinh = $_POST['ngaysinh'];
							$ss = false;
							$chk_haveAvatar = false;

							if (isset($_FILES['avatar'])){
								$ss = false;
								$file = $_FILES['avatar'];
								$fileName = $file['name'];
								$fileSize = $file['size'];
								$fileTemp = $file['tmp_name'];
								$filePath = './files/images/avatars/' . $currentuser['userID'] . '.jpg';
								$ss = move_uploaded_file($fileTemp, $filePath);
								$newImage = resizeImage($filePath, 480, 480);
								imagejpeg($newImage, $filePath);

								$chk_haveAvatar = true;
							}

							if ($displayName == '') {				
								$displayName = $currentuser['displayName'];
								updateUserInfo($currentuser['userID'], $displayName, $phone, $ngaysinh, $currentuser['haveAvatar']);

								$ss = true;
							}
							if ($phone == '') {
								$phone = $currentuser['phone'];
								updateUserInfo($currentuser['userID'], $displayName, $phone, $ngaysinh, $currentuser['haveAvatar']);

								$ss = true;
							}
							if ($ngaysinh == '') {
								$ngaysinh = $currentuser['ngaysinh'];
								updateUserInfo($currentuser['userID'], $displayName, $phone, $ngaysinh, $currentuser['haveAvatar']);

								$ss = true;
							}
							if ($chk_haveAvatar == true) {
								updateUserInfo($currentuser['userID'], $currentuser['displayName'], $currentuser['phone'], $currentuser['ngaysinh'], $currentuser['userID']);

								$ss = true;
							}

							header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
							header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
						?>
						<?php if ($ss): ?>
						<?php header('Location: index.php') ?>
						<?php else: ?>
							<div class="alert alert-danger text-center" role="alert">
								Đổi thông tin cá nhân thất bại!
							</div>
						<?php endif; ?>
					<?php else: ?>
						<form action="update-info.php" method="POST" enctype="multipart/form-data" class="form-signin">
							<div class="form-label-group">
								<input type="text" class="form-control" id="displayName" name="displayName" aria-describedby="numHelp" placeholder="<?php echo $currentuser['displayName'] ?>">
								<label for="displayName">Họ và tên: <?php echo $currentuser['displayName'] ?></label>
							</div>
							<div class="form-label-group">
								<input type="text" class="form-control" id="phone" name="phone" aria-describedby="numHelp" placeholder="<?php echo $currentuser['phone'] ?>">
								<label for="phone">Số điện thoại: <?php echo $currentuser['phone'] ?></label>
							</div>
							<div class="form-label-group">
								<input type="date" class="form-control" id="ngaysinh" name="ngaysinh" aria-describedby="numHelp" placeholder="<?php echo $currentuser['ngaysinh'] ?>">
								<label for="ngaysinh">Ngày sinh: <?php echo $currentuser['ngaysinh'] ?></label>
							</div>
							<div class="form-label-group">
								<input type="file" class="form-control" id="avatar" name="avatar">
								<label for="avatar">Ảnh đại diện</label>
							</div>
							<button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">Đổi thông tin</button>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>	
</div> <!-- /container -->
<?php include 'footer.php'; ?>