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
<h1 class="card-title text-center fadein_animation">Đổi mật khẩu</h1>
	<?php if (isset($_POST['currentPassword']) && isset($_POST['password'])): ?>
		<?php 
			$curretpassword = $_POST['currentPassword'];
			$password = $_POST['password'];
			$ss = false;

			if (password_verify($curretpassword, $currentuser['password'])) {
				updateUserPassword($currentuser['userID'], $password);

				$ss = true;
			}
		?>
		<?php if ($ss): ?>
		<?php header('Location: index.php') ?>
		<?php else: ?>
			<div class="alert alert-danger text-center" role="alert">
				Đổi mật khẩu thất bại!
			</div>
		<?php endif; ?>
	<?php else: ?>
		<form action="change-password.php" method="POST" class="form-signin">
			<div class="form-label-group">
				<input type="password" class="form-control" id="currentPassword" name="currentPassword" aria-describedby="numHelp" placeholder="Mật khẩu hiện tại" required autofocus>
				<label for="currentPassword">Mật khẩu hiện tại</label>
			</div>
			<div class="form-label-group">
				<input type="password" class="form-control" id="password" name="password" aria-describedby="numHelp" placeholder="Mật khẩu mới" required>
				<label for="password">Mật khẩu mới</label>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">Đổi mật khẩu</button>
		</form>
	<?php endif; ?>
</div>
</div>
</div>
</div>	
</div> <!-- /container -->
<?php include 'footer.php'; ?>
