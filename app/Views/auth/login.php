<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= base_url('public/')?>armada.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('public/')?>plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('public/')?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('public/')?>dist/css/adminlte.min.css">

    <title>Login</title>
</head>
<body>

<body class="hold-transition login-page" style="background-image: url('<?= base_url('public/')?>dist/img/majbg.jpg'); background-color: rgba(0, 0, 0, 0.5); background-blend-mode: overlay;">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary" style="opacity: 0.8;">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>E - </b>INVOICE</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to Portal Supplier MAJ</p>

      <form action="<?= base_url('auth/login') ?>" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="<?= lang('Auth.login_identity_label') ?>" id="username" name="identity">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="<?= lang('Auth.login_password_label') ?>" id="password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember" class="control control--checkbox mb-0"><span class="caption"><?= lang('Auth.login_remember_label') ?></span></label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-block btn-primary"><?= lang('Auth.login_submit_btn') ?></button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mb-1">
        <a href="<?= base_url('auth/forgot_password') ?>" class="forgot-pass"><?= lang('Auth.login_forgot_password') ?></a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?= base_url('public/')?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('public/')?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('public/')?>dist/js/adminlte.min.js"></script>
</body>
</html>
