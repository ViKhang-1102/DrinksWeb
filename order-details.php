<?php
session_start();
require_once __DIR__ . '/config/database.php';

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($user_id === 0) {
    header('Location: authentication-login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: orders.php');
    exit;
}
$order_id = (int)$_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();
if (!$order) {
    echo '<div class="container py-5"><div class="alert alert-danger">Order not found.</div></div>';
    include 'footer.php';
    exit;
}

$sql = 'SELECT od.*, p.name as product_name, p.thumbnail FROM order_details od 
        JOIN products p ON od.product_id = p.id WHERE od.order_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$order_details = $stmt->fetchAll();

include 'header.php';
?>
<div class="container py-5" style="margin-top: 90px;">
    <h2 class="mb-4">Order Details</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Order Info</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Order Code:</strong> <?= htmlspecialchars($order['order_code']) ?></li>
                <li class="list-group-item"><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></li>
                <li class="list-group-item"><strong>Subtotal:</strong> <?= number_format($order['subtotal'], 0, ',', '.') ?>₫</li>
                <li class="list-group-item"><strong>Tax Fee:</strong> <?= number_format($order['tax_fee'], 0, ',', '.') ?>₫</li>
                <li class="list-group-item"><strong>Total:</strong> <?= number_format($order['total'], 0, ',', '.') ?>₫</li>
                <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($order['phone'] ?? '') ?></li>
                <li class="list-group-item"><strong>Address:</strong> <?= htmlspecialchars($order['address'] ?? '') ?></li>
                <li class="list-group-item"><strong>Note:</strong> <?= nl2br(htmlspecialchars($order['note'])) ?></li>
                <li class="list-group-item"><strong>Created At:</strong> <?= $order['created_at'] ?></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Order Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_details as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item['thumbnail']): ?>
                                    <img src="assets/images/product-images/<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" width="40" class="me-2 rounded">
                                <?php endif; ?>
                                <?= htmlspecialchars($item['product_name']) ?>
                            </td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="orders.php" class="btn btn-secondary mt-3">Back to Orders</a>
</div>
<?php include 'footer.php'; ?>
