<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>401 Error</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('public/')?>plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('public/')?>dist/css/adminlte.min.css">
</head>
<body>
    <div class="error-page">
        <h2 class="headline text-danger">401</h2>

        <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i>!!Unauthorized!!</h3>
        <p>
            You do not have permission to access this page. Please log in to continue
        </p>
        <form class="search-form">
            <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search">
            <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
                </button>
            </div>
            </div>
        </form>
        </div>
    </div>
    <!-- /.error-page -->
<script src="<?= base_url('public/')?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('public/')?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('public/')?>dist/js/adminlte.min.js"></script>
</body>

</html>
