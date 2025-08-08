<?php
session_start();
include 'header.php';
require_once 'config/database.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email === '') {
        $message = '<div class="alert alert-danger">Please enter an email.</div>';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', time() + 3600);
            $pdo->prepare('UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?')->execute([$token, $expiry, $user['id']]);
            $base_url = defined('APP_URL') ? APP_URL : (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            $reset_link = $base_url . "/reset-password.php?token=$token";
            $message = '<div class="alert alert-success">A password reset link has been generated:<br><a href="' . $reset_link . '">' . $reset_link . '</a></div>';
        } else {
            $message = '<div class="alert alert-warning">If the email exists, a password reset guide has been sent.</div>';
        }
    }
}
?>

<!--start page content-->
<div class="page-content">
  <div class="py-4 border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="javascript:;">Authentication</a></li>
          <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
        </ol>
      </nav>
    </div>
  </div>
  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
          <div class="card rounded-0">
            <div class="card-body p-4">
              <h4 class="mb-0 fw-bold text-center">Forgot Password</h4>
              <hr>
              <?= $message ?>
              <form method="post" autocomplete="off">
                <div class="mb-3">
                  <label for="forgotEmail" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control rounded-0" id="forgotEmail" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-dark rounded-0 btn-ecomm w-100">Send Reset Link</button>
              </form>
              <div class="mt-3 text-center">
                <a href="authentication-login.php" class="text-secondary">Back to Login</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php include 'footer.php'; ?>