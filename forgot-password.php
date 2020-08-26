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
<h1 class="card-title text-center fadein_animation">Quên mật khẩu</h1>
	<?php if (isset($_POST['email'])): ?>
		<?php 
			$email = $_POST['email'];
			$ss = false;

			$user = findUserByEmail($email);

			if ($user) {
				$ss = forgotPass($user['userID']);

				$ss = true;
			}
		?>
		<?php if ($ss): ?>
            <div class="alert alert-success text-center" role="alert">
				Vui lòng kiểm tra email để lấy lại mật khẩu!
			</div>
		<?php else: ?>
			<div class="alert alert-danger text-center" role="alert">
				Email vừa nhập không tồn tại!
			</div>
		<?php endif; ?>
	<?php else: ?>
		<form action="forgot-password.php" method="POST" class="form-signin">
			<div class="form-label-group">
				<input type="email" class="form-control" id="email" name="email" aria-describedby="numHelp" placeholder="Nhập Email quên mật khẩu" required autofocus>
				<label for="email">Nhập địa chỉ Email</label>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">Lấy lại mật khẩu</button>
		</form>
	<?php endif; ?>
</div>
</div>
</div>
</div>	
</div> <!-- /container -->
<?php include 'footer.php'; ?>