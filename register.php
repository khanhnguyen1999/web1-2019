<?php 
  require_once 'init.php';
  if ($currentuser) {
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
<h1 class="card-title text-center fadein_animation">Tạo tài khoản mới</h1>
	<?php if (isset($_POST['displayName']) && isset($_POST['email']) && isset($_POST['password'])): ?>
		<?php 
			$displayName = $_POST['displayName'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$phone = $_POST['phone'];
			$rawdate = htmlentities($_POST['ngaysinh']);
			$ngaysinh = date('Y-m-d', strtotime($rawdate));
			$ss = false;

			$user = findUserByEmail($email);

			if (!$user) {
				$newUserID = createUser($displayName, $email, $password, $phone, $ngaysinh);
				// $_SESSION['userId'] = $newUserID;
				$_currentuser = findUserById($newUserID);
				$ss = true;
			}

			// if (is_uploaded_file($_FILES['avatar']['tmp_name'])){
			// 	$ss = false;
			// 	$file = $_FILES['avatar'];
			// 	$fileName = $file['name'];
			// 	$fileSize = $file['size'];
			// 	$fileTemp = $file['tmp_name'];
			// 	$filePath = './files/images/avatars/' . $_currentuser['userID'] . '.jpg';
			// 	$ss = move_uploaded_file($fileTemp, $filePath);
			// 	$newImage = resizeImage($filePath, 480, 480);
			// 	imagejpeg($newImage, $filePath);
			// }
		?>
		<?php if ($ss): ?>
		<div class="alert alert-success text-center" role="alert">
				Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản!
			</div>
		<?php else: ?>
			<div class="alert alert-danger text-center" role="alert">
				Đăng ký thất bại!
			</div>
		<?php endif; ?>
	<?php else: ?>
		<form action="register.php" method="POST" enctype="multipart/form-data" class="form-signin">
			<div class="form-label-group">
				<input type="text" class="form-control" id="displayName" name="displayName" aria-describedby="numHelp" placeholder="Họ tên của bạn" required autofocus>
				<label for="displayName">Họ tên của bạn</label>
			</div>
			<div class="form-label-group">
				<input type="email" class="form-control" id="email" name="email" aria-describedby="numHelp" placeholder="Email đăng ký" required>
				<label for="email">Email đăng ký</label>
			</div>
			<div class="form-label-group">
				<input type="password" class="form-control" id="password" name="password" aria-describedby="numHelp" placeholder="Mật khẩu mới" required>
				<label for="password">Mật khẩu mới</label>
			</div>
			<div class="form-label-group">
				<input type="text" class="form-control" id="phone" name="phone" aria-describedby="numHelp" placeholder="Số điện thoại">
				<label for="phone">Số điện thoại</label>
			</div>
			<div class="form-label">
				<label for="datepicker">Ngày sinh</label>
				<input type="text" class="form-control" id="datepicker" name="ngaysinh" aria-describedby="numHelp" placeholder="Ngày sinh">
				<script>
					$('#datepicker').datepicker();
				</script>
				<br>
				<small id="numHelp" class="form-text text-muted">Bằng cách nhấp vào Đăng ký, bạn đồng ý với Điều khoản, Chính sách dữ liệu và Chính sách cookie của chúng tôi. Bạn có thể nhận được thông báo của chúng tôi qua SMS và hủy nhận bất kỳ lúc nào.</small>
			</div>
			<!-- <div class="form-label-group">
				<label for="avatar">Thêm ảnh Avatar</label>
				<input type="file" class="form-control-file" id="avatar" name="avatar">
			</div> -->
			<br>
			<button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">Đăng ký</button>
		</form>
	<?php endif; ?>
</div>
</div>
</div>
</div>
</div> <!-- /container -->
<?php include 'footer.php'; ?>
