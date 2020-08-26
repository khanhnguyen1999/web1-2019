<?php 
  require_once 'init.php';
?>
<?php include 'header.php'; ?>
<h1>Kích hoạt tài khoản</h1>
	<?php if (isset($_GET['code'])): ?>
		<?php 
			$code = $_GET['code'];
			$ss = false;

            $ss = activateUser($code);
		?>
		<?php if ($ss): ?>
		<?php header('Location: login.php') ?>
		<?php else: ?>
			<div class="alert alert-danger" role="alert">
				Kích hoạt tài khoản thất bại!
			</div>
		<?php endif; ?>
	<?php else: ?>
		<form method="GET">
			<div class="form-group">
				<label for="code">Kích hoạt tài khoản</label>
				<input type="text" class="form-control" id="code" name="code" aria-describedby="numHelp" placeholder="Mã kích hoạt">
				<small id="numHelp" class="form-text text-muted">Hãy nhập code kích hoạt!</small>
			</div>
			<button type="submit" class="btn btn-primary">Kích hoạt</button>
		</form>
	<?php endif; ?>
<?php include 'footer.php'; ?>
