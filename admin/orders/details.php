<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$order_id = intval($_GET['id']);

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order) {
    echo '<div class="alert alert-danger">Order not found.</div>';
    exit;
}

$sql = 'SELECT od.*, p.name as product_name FROM order_details od 
        JOIN products p ON od.product_id = p.id WHERE od.order_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$order_details = $stmt->fetchAll();

include '../admin_header.php';
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Order Details #<?= $order['id'] ?></h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Order Info</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Order Code:</strong> <?= htmlspecialchars($order['order_code']) ?></li>
                <li class="list-group-item"><strong>User ID:</strong> <?= $order['user_id'] ?></li>
                <li class="list-group-item"><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></li>
                <li class="list-group-item"><strong>Subtotal:</strong> <?= number_format($order['subtotal'], 2) ?></li>
                <li class="list-group-item"><strong>Tax Fee:</strong> <?= number_format($order['tax_fee'], 2) ?></li>
                <li class="list-group-item"><strong>Total:</strong> <?= number_format($order['total'], 2) ?></li>
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
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_details as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="index.php" class="btn btn-secondary mt-3">Back to Orders</a>
</div>
<?php include '../admin_footer.php'; ?>
