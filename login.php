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
<h1 class="card-title text-center fadein_animation">Đăng nhập TeamX</h1>
	<?php if (isset($_POST['email']) && isset($_POST['password'])): ?>
		<?php 
			$email = $_POST['email'];
			$password = $_POST['password'];
			$ss = false;

			$user = findUserByEmail($email);

			if ($user && $user['chk_status'] == 1 && password_verify($password, $user['password'])) {
				$ss = true;
				$_SESSION['userId'] = $user['userID'];
			}
		?>
		<?php if ($ss): ?>
		<?php header('Location: index.php') ?>
		<?php else: ?>
			<div class="alert alert-danger text-center" role="alert">
				Đăng nhập thất bại!
			</div>
		<?php endif; ?>
	<?php else: ?>
		<form action="login.php" method="POST" class="form-signin">
			<div class="form-label-group">
				<input type="email" class="form-control" id="email" name="email" aria-describedby="numHelp" placeholder="Email đăng nhập" required autofocus>
				<label for="email">Email đăng nhập</label>
			</div>
			<div class="form-label-group">
				<input type="password" class="form-control" id="password" name="password" aria-describedby="numHelp" placeholder="Mật khẩu" required>
				<label for="password">Mật khẩu</label>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">Đăng nhập</button>
			<hr class="my-4">
			<div class="form-label-group text-center">
				<h5><a href="forgot-password.php" class="badge badge-pill badge-info">Bạn quên mật khẩu?</a></h5>
			</div>
		</form>
	<?php endif; ?>
</div>
</div>
</div>
</div>	
</div> <!-- /container -->
<?php include 'footer.php'; ?>
