<?php 
  require_once 'init.php';

  unset($_SESSION['userIdProfile']);
?>
<?php include 'header.php'; ?>
<?php if ($currentuser): ?>
        <div class="row">
          
            <!-- Menu Left /////-->
            <?php include 'menu-left.php'; ?>
            <!-- EndMenu Left /////-->

            <div class="col-md-6 gedf-main">

                <!-- Box Status /////-->
                <?php include 'box-post-status.php'; ?>
                <!-- End Box Status /////-->

                <!--- \\\\\\\Load Status-->
                <div id="load_status"></div>
                <div id="load_status_message"></div>
                <!-- Load Status /////-->

            </div>

            <!-- Box Right /////-->
            <?php include 'box-right.php'; ?>
            <!-- EndMenu Box Right /////-->

        </div>
    </div>

<?php else: ?>

<main role="main" class="container">
  <div class="row">

    <div class="col-md-8 text-center">
        <div class="login_title_header fadein_animation">TeamX Mạng Xã Hội của người Việt</div>
        <div class="login_title_info">TeamX giúp bạn kết nối và chia sẻ với mọi người trong cuộc sống của bạn.</div>
        <div class="login_title_img"><img src="files\images\login\social_network.png" class="img-fluid" alt="Responsive image"></div>
    </div>

    <div class="col-md-4">

      <div class="float-md-right">

        <div class="card">
          <form action="login.php" method="POST">
            <div class="form-group">
              <input type="email" class="form-control" id="email" name="email" aria-describedby="numHelp" placeholder="Email đăng nhập">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="password" name="password" aria-describedby="numHelp" placeholder="Mật khẩu">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-right:15px">Đăng nhập</button>
            <a href="forgot-password.php" class="card-link">Quên mật khẩu?</a>
          </form>
        </div>

        <div class="my-4 separator">Hay</div>
        
        <div class="card">
          <h2 class="ij_header">Lần đầu vào TeamX?</h2>
          <div class="ij_subheader">Đăng ký miễn phí!</div>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary-register" data-toggle="modal" data-target="#exampleModalCenter">Đăng ký</button>

              <!-- Modal -->
              <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Đăng ký tài khoản miễn phí!</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="register.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                          <input type="text" class="form-control" id="displayName" name="displayName" aria-describedby="numHelp" placeholder="Họ tên đầy đủ của bạn" required autofocus>
                        </div>
                        <div class="form-group">
                          <input type="email" class="form-control" id="email" name="email" aria-describedby="numHelp" placeholder="Email đăng ký" required>
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control" id="password" name="password" aria-describedby="numHelp" placeholder="Mật khẩu mới" required>
                        </div>
                        <div class="form-group">
                          <input type="text" class="form-control" id="phone" name="phone" aria-describedby="numHelp" placeholder="Số điện thoại">
                        </div>
                        <div class="form-group">
                          <label for="ngaysinh">Ngày sinh</label>
                          <input type="text" class="form-control" id="datepicker" name="ngaysinh" aria-describedby="numHelp" placeholder="Ngày/tháng/năm sinh">
                          <script>
                              $('#datepicker').datepicker();
                          </script>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                          <button type="submit" class="btn btn-primary">Đăng ký ngay!</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>

    </div>

  </div><!-- /row -->
</main> <!-- /main -->

<?php endif ?>
<?php include 'footer.php'; ?>