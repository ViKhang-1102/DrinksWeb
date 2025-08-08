<?php
session_start();
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['logout_success'] = 'Log out successfully!';
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
?>Log out successfully!

  <!--start page content-->
  <div class="page-content">

    <!--start breadcrumb-->
    <div class="py-4 border-bottom">
      <div class="container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:;">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <!--start product details-->
    <section class="section-padding">
      <div class="container">
        <div class="d-flex align-items-center px-3 py-2 border mb-4">
          <div class="text-start">
            <h4 class="mb-0 h4 fw-bold">Account - Profile</h4>
          </div>
        </div>
        <div class="btn btn-dark btn-ecomm d-xl-none position-fixed top-50 start-0 translate-middle-y" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbarFilter"><span><i class="bi bi-person me-2"></i>Account</span></div>
        <div class="row">
          <div class="col-12 col-xl-3 filter-column">
            <div class="list-group w-100 rounded-0">
              <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="bi bi-pencil me-2"></i>Edit Profile</a>
              <a href="account-profile.php?action=logout" class="list-group-item"><i class="bi bi-power me-2"></i>Logout</a>
            </div>
          </div>
          <div class="col-12 col-xl-9">
            <div class="card rounded-0">
              <div class="card-body p-lg-5">
                <h5 class="mb-0 fw-bold">Profile Details</h5>
                <hr>
                <?php
                if (empty($_SESSION['user_id'])) {
                  echo '<div class="alert alert-info">You are not logged in.</div>';
                } else {
                  require_once 'config/database.php';
                  $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
                  $stmt->execute([$_SESSION['user_id']]);
                  $user = $stmt->fetch();
                  if (!$user) {
                    echo '<div class="alert alert-danger">User not found.</div>';
                  } else {
                ?>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <tbody>
                      <tr><td>Name</td><td><?= htmlspecialchars($user['name'] ?? '') ?></td></tr>
                      <tr><td>Email</td><td><?= htmlspecialchars($user['email'] ?? '') ?></td></tr>
                      <?php if (!empty($user['phone'])): ?><tr><td>Phone</td><td><?= htmlspecialchars($user['phone']) ?></td></tr><?php endif; ?>
                      <?php if (!empty($user['gender'])): ?><tr><td>Gender</td><td><?= htmlspecialchars($user['gender']) ?></td></tr><?php endif; ?>
                      <?php if (!empty($user['dob'])): ?><tr><td>DOB</td><td><?= htmlspecialchars($user['dob']) ?></td></tr><?php endif; ?>
                      <?php if (!empty($user['address'])): ?><tr><td>Address</td><td><?= htmlspecialchars($user['address']) ?></td></tr><?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <?php }} ?>
              </div>
            </div>
          </div>
        </div><!--end row-->
      </div>
    </section>
    <!--start product details-->


    <!-- filter Modal -->
    <div class="modal" id="FilterOrders" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-0">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Filter Orders</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h6 class="mb-3 fw-bold">Status</h6>
            <div class="status-radio d-flex flex-column gap-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                <label class="form-check-label" for="flexRadioDefault1">
                  All
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2">
                  On the way
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                <label class="form-check-label" for="flexRadioDefault3">
                  Delivered
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault4">
                <label class="form-check-label" for="flexRadioDefault4">
                  Cancelled
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault5">
                <label class="form-check-label" for="flexRadioDefault5">
                  Returned
                </label>
              </div>
            </div>
            <hr>
            <h6 class="mb-3 fw-bold">Time</h6>
            <div class="status-radio d-flex flex-column gap-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioTime" id="flexRadioDefault6" checked>
                <label class="form-check-label" for="flexRadioDefault6">
                  Anytime
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioTime" id="flexRadioDefault7">
                <label class="form-check-label" for="flexRadioDefault7">
                  Last 30 days
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioTime" id="flexRadioDefault8">
                <label class="form-check-label" for="flexRadioDefault8">
                  Last 6 months
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioTime" id="flexRadioDefault9">
                <label class="form-check-label" for="flexRadioDefault9">
                  Last year
                </label>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <div class="d-flex align-items-center gap-3 w-100">
              <button type="button" class="btn btn-outline-dark btn-ecomm w-50">Clear Filters</button>
              <button type="button" class="btn btn-dark btn-ecomm w-50">Apply</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end Filters Modal -->

  </div>
  <!--end page content-->

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php
          // Reload user info for modal
          $editUser = null;
          if (!empty($_SESSION['user_id'])) {
            require_once 'config/database.php';
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $editUser = $stmt->fetch();
          }
          ?>
          <div class="mb-3">
            <label for="editName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editName" name="edit_name" value="<?= htmlspecialchars($editUser['name'] ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label for="editPhone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="editPhone" name="edit_phone" value="<?= htmlspecialchars($editUser['phone'] ?? '') ?>">
          </div> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="save_profile">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
// Handle profile update
if (isset($_POST['save_profile']) && !empty($_SESSION['user_id'])) {
  $name = trim($_POST['edit_name']);
  $phone = trim($_POST['edit_phone']);
  $gender = trim($_POST['edit_gender']);
  $dob = trim($_POST['edit_dob']);
  $address = trim($_POST['edit_address']);
  require_once 'config/database.php';
  $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ?, gender = ?, dob = ?, address = ? WHERE id = ?');
  $stmt->execute([$name, $phone, $gender, $dob, $address, $_SESSION['user_id']]);
  echo '<script>location.reload();</script>';
  exit;
}
?>

<?php include 'footer.php'; ?>