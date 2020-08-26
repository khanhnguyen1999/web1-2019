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
<h1 class="card-title text-center fadein_animation">Đặt lại mật khẩu</h1>
	<?php if (isset($_GET['passNew']) && isset($_GET['passNewRe'])): ?>
		<?php 
			$code = $_GET['code'];
			$passNew = $_GET['passNew'];
			$passNewRe = $_GET['passNewRe'];
			$ss = false;

			if ($passNew == $passNewRe) {
				$ss = activatePassChange($code, $passNew);
			}
		?>
		<?php if ($ss): ?>
		<?php header('Location: login.php') ?>
		<?php else: ?>
			<div class="alert alert-danger text-center" role="alert">
				Đặt lại mật khẩu thất bại!
			</div>
		<?php endif; ?>
	<?php else: ?>
		<form method="GET" class="form-signin">
            <div class="form-label-group">
				<input type="password" class="form-control" id="passNew" name="passNew" aria-describedby="numHelp" placeholder="Mật khẩu mới" required autofocus>
				<label for="passNew">Mật khẩu mới</label>
			</div>
			<div class="form-label-group">
				<input type="password" class="form-control" id="passNewRe" name="passNewRe" aria-describedby="numHelp" placeholder="Nhập lại mật khẩu mới" required>
				<label for="passNewRe">Nhập lại mật khẩu mới</label>
			</div>
			<div class="form-label-group">
				<input type="text" class="form-control" value="<?php echo $_GET['code']; ?>" id="code" name="code" aria-describedby="numHelp" placeholder="Code đổi mật khẩu" readonly required>
				<label for="code">Code đổi mật khẩu</label>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">Đặt lại mật khẩu</button>
		</form>
	<?php endif; ?>
</div>
</div>
</div>
</div>	
</div> <!-- /container -->
<?php include 'footer.php'; ?>
