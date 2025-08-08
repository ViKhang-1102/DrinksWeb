<?php
session_start();
require_once __DIR__ . '/config/database.php';

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($user_id === 0) {
    header('Location: authentication-login.php');
    exit;
}

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
        $stmt = $pdo->prepare('INSERT INTO orders (user_id, order_code, status, subtotal, tax_fee, total, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $order_code, 'pending', $subtotal, $default_tax_fee, $total, $now, $now]);
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
<div class="container py-5" style="margin-top: 90px;">
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
