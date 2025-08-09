<?php
session_start();
require_once __DIR__ . '/config/database.php';

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($user_id === 0) {
    header('Location: authentication-login.php');
    exit;
}

// Handle update default info
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_default_info'])) {
    $phone = trim($_POST['default_phone'] ?? '');
    $address = trim($_POST['default_address'] ?? '');
    $stmt = $pdo->prepare('UPDATE users SET phone = ?, address = ? WHERE id = ?');
    $stmt->execute([$phone, $address, $user_id]);
    $_SESSION['profile_success'] = 'Default information updated successfully!';
}

$user_stmt = $pdo->prepare('SELECT phone, address FROM users WHERE id = ?');
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $stmt = $pdo->prepare('SELECT c.product_id, c.quantity, p.price FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetchAll();
    if (!empty($cart)) {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $default_tax_fee = 0;
        $tax_fee_file = __DIR__ . '/admin/orders/default_tax_fee.txt';
        if (file_exists($tax_fee_file)) {
            $default_tax_fee = (float)file_get_contents($tax_fee_file);
        }
        $total = $subtotal + $default_tax_fee;
        $order_code = 'ORD-' . date('YmdHis') . '-' . substr(md5(uniqid()), 0, 5);
        $now = date('Y-m-d H:i:s');
        
        $userInfo = $pdo->prepare('SELECT phone, address FROM users WHERE id = ?');
        $userInfo->execute([$user_id]);
        $userRow = $userInfo->fetch();
        $phone = $userRow['phone'] ?? '';
        $address = $userRow['address'] ?? '';
        $stmt = $pdo->prepare('INSERT INTO orders (user_id, order_code, status, subtotal, tax_fee, total, created_at, updated_at, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $order_code, 'pending', $subtotal, $default_tax_fee, $total, $now, $now, $phone, $address]);
        $order_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare('INSERT INTO order_details (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)');
        foreach ($cart as $item) {
            $stmt->execute([$order_id, $item['product_id'], $item['price'], $item['quantity']]);
        }
        $pdo->prepare('DELETE FROM carts WHERE user_id = ?')->execute([$user_id]);
        unset($_SESSION['cart']);
        header('Location: orders.php?msg=ordered');
        exit;
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $order_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();
    if ($order) {
        $created = strtotime($order['created_at']);
        if (time() - $created <= 2 * 24 * 60 * 60) { 
            $pdo->prepare('DELETE FROM order_details WHERE order_id = ?')->execute([$order_id]);
            $pdo->prepare('DELETE FROM orders WHERE id = ?')->execute([$order_id]);
            header('Location: orders.php?msg=deleted');
            exit;
        }
    }
}
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($user_id === 0) {
    header('Location: authentication-login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();


include 'header.php';
?>
<div class="container py-4" style="margin-top: 90px;">
    <div class="card mb-4">
        <div class="card-header fw-bold">Set up phone number and address for the order</div>
        <div class="card-body">
            <?php if (!empty($_SESSION['profile_success'])): ?>
                <div class="alert alert-success"> <?= $_SESSION['profile_success']; unset($_SESSION['profile_success']); ?> </div>
            <?php endif; ?>
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label for="default_phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="default_phone" name="default_phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="default_address" class="form-label"><Address></Address></label>
                    <input type="text" class="form-control" id="default_address" name="default_address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" required>
                </div>
                <div class="col-12">
                    <button type="submit" name="update_default_info" class="btn btn-primary">Save information</button>
                </div>
            </form>
        </div>
    </div>
    <h2 class="mb-4">My Orders</h2>
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">You have no orders yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Order Code</th>
                        <th>Status</th>
                        <th>Subtotal</th>
                        <th>Tax Fee</th>
                        <th>Total</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_code']) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td><?= number_format($order['subtotal'], 0, ',', '.') ?>₫</td>
                            <td><?= number_format($order['tax_fee'], 0, ',', '.') ?>₫</td>
                            <td><?= number_format($order['total'], 0, ',', '.') ?>₫</td>
                            <td><?= $order['created_at'] ? date('d/m/Y H:i', strtotime($order['created_at'])) : '' ?></td>
                            <td>
                                <a href="order-details.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Details</a>
                                <?php if ($order['created_at'] && (time() - strtotime($order['created_at']) <= 2 * 24 * 60 * 60)): ?>
                                    <a href="orders.php?delete=<?= $order['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
