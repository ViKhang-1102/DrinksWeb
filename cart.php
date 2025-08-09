<?php
session_start();
require_once __DIR__ . '/config/database.php';

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($user_id === 0) {
    header('Location: authentication-login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
        $pid = (int)$_POST['product_id'];
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$user_id, $pid]);
        if ($stmt->fetch()) {
            $update = $pdo->prepare('UPDATE carts SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?');
            $update->execute([$qty, $user_id, $pid]);
        } else {
            $insert = $pdo->prepare('INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)');
            $insert->execute([$user_id, $pid, $qty]);
        }
        header('Location: cart.php');
        exit;
    }
    if (isset($_POST['update_qty']) && isset($_POST['pid']) && isset($_POST['qty'])) {
        $pid = (int)$_POST['pid'];
        $qty = max(1, (int)$_POST['qty']);
        $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$user_id, $pid]);
        if ($stmt->fetch()) {
            $update = $pdo->prepare('UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?');
            $update->execute([$qty, $user_id, $pid]);
        }
        header('Location: cart.php');
        exit;
    }
    if (isset($_POST['remove_pid'])) {
        $pid = (int)$_POST['remove_pid'];
        $delete = $pdo->prepare('DELETE FROM carts WHERE user_id = ? AND product_id = ?');
        $delete->execute([$user_id, $pid]);
        header('Location: cart.php');
        exit;
    }
}

include 'header.php';
$products = [];
$total = 0;
$cart_stmt = $pdo->prepare('SELECT c.product_id, c.quantity, p.* FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?');
$cart_stmt->execute([$user_id]);
while ($row = $cart_stmt->fetch()) {
    $row['cart_qty'] = $row['quantity'];
    $row['cart_total'] = $row['cart_qty'] * $row['price'];
    $products[] = $row;
    $total += $row['cart_total'];
}
$stmt = $pdo->prepare('SELECT c.*, p.name, p.price, p.thumbnail FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?');
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();
$total = 0;
?>
      <style>
        .cart-main-wrapper {
          max-width: 960px;
          margin: 0 auto;
          padding: 80px 0 24px 0;
        }
        .cart-main-wrapper .card {
          margin-bottom: 18px;
        }
        .cart-main-wrapper .cart-img-box img {
          width: 90px;
          height: 90px;
        }
        .cart-main-wrapper .order-summary-card {
          margin-top: 24px;
        }
        .cart-main-wrapper .order-summary-card .btn-success {
          font-size: 1.1rem;
          padding: 12px 32px;
        }
        @media (max-width: 768px) {
          .cart-main-wrapper {
            max-width: 100%;
            padding: 16px 0 12px 0;
          }
          .cart-main-wrapper .card-body {
            flex-direction: column !important;
            gap: 12px !important;
          }
          .cart-main-wrapper .cart-img-box img {
            width: 70px;
            height: 70px;
          }
        }
      </style>
      <div class="cart-main-wrapper">
        <div class="py-4 border-bottom mb-4">
          <div class="container">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
              </ol>
            </nav>
          </div>
        </div>
        <h2 class="fw-bold mb-4 text-center">🛒 My Bag (<?= array_sum(array_column($cart_items, 'quantity')) ?> items)</h2>
        <div class="text-end mb-3">
          <a href="index.php" class="btn btn-light btn-ecomm">Continue Shopping</a>
        </div>
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info text-center">Your cart is empty.</div>
        <?php else: ?>
          <div class="row g-4">
            <div class="col-12 col-lg-8">
              <?php foreach ($cart_items as $item): ?>
                <div class="card shadow-sm border-0">
                  <div class="card-body d-flex flex-row align-items-center gap-3">
                    <div class="cart-img-box">
                      <?php if ($item['thumbnail']): ?>
                        <img src="assets/images/product-images/<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="rounded">
                      <?php else: ?>
                        <img src="assets/images/no-image.png" alt="No image" class="rounded">
                      <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                      <h5 class="fw-bold mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                      <div class="mb-2 text-muted">Price: <span class="fw-bold text-dark"><?= number_format($item['price'], 0, ',', '.') ?>₫</span></div>
                      <form method="post" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="pid" value="<?= $item['product_id'] ?>">
                        <input type="number" name="qty" value="<?= $item['quantity'] ?>" min="1" class="form-control form-control-sm" style="width:60px;">
                        <button type="submit" name="update_qty" class="btn btn-sm btn-dark">Update</button>
                      </form>
                    </div>
                    <div class="text-end">
                      <div class="mb-2">Total: <span class="fw-bold text-success h6"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫</span></div>
                      <form method="post" style="display:inline">
                        <input type="hidden" name="remove_pid" value="<?= $item['product_id'] ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-lg"></i> Remove</button>
                      </form>
                    </div>
                  </div>
                </div>
                <?php $total += $item['price'] * $item['quantity']; ?>
              <?php endforeach; ?>
            </div>
            <div class="col-12 col-lg-4">
              <div class="card order-summary-card border-0 shadow-sm">
                <div class="card-body">
                  <h5 class="fw-bold mb-2">Order Summary</h5>
                  <div class="mb-1">Bag Total: <span class="fw-bold text-dark"><?= number_format($total, 0, ',', '.') ?>₫</span></div>
                  <div class="mb-1">Total Amount: <span class="fw-bold text-success h5"><?= number_format($total, 0, ',', '.') ?>₫</span></div>
                  <div class="d-grid mt-4">
                    <form method="post" action="orders.php">
                      <input type="hidden" name="place_order" value="1">
                      <?php
                      $cart_db = $pdo->prepare('SELECT c.product_id, c.quantity, p.price, p.name FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?');
                      $cart_db->execute([$user_id]);
                      $cart_session = [];
                      while ($row = $cart_db->fetch()) {
                          $cart_session[] = [
                              'product_id' => $row['product_id'],
                              'quantity' => $row['quantity'],
                              'price' => $row['price'],
                              'name' => $row['name']
                          ];
                      }
                      $_SESSION['cart'] = $cart_session;
                      ?>
                      <button type="submit" class="btn btn-success">Place an Order</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
<!--end top header-->
</div>
<!--end page content-->

<?php include 'footer.php'; ?>
</div>
<!--end-->

<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class="bi bi-arrow-up"></i></a>
<!--End Back To Top Button-->


<!-- JavaScript files -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/plugins/slick/slick.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/loader.js"></script>

</body>
</html>