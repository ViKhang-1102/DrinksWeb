<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

$default_tax_fee_file = dirname(__FILE__) . '/default_tax_fee.txt';
$new_tax_fee = null;
if (isset($_POST['set_tax_fee'])) {
    $new_tax_fee = floatval($_POST['default_tax_fee']);
    file_put_contents($default_tax_fee_file, $new_tax_fee);
    $stmt = $pdo->prepare('UPDATE orders SET tax_fee = ?, total = subtotal + ?');
    $stmt->execute([$new_tax_fee, $new_tax_fee]);
}
$default_tax_fee = file_exists($default_tax_fee_file) ? file_get_contents($default_tax_fee_file) : 0;

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    $pdo->prepare('DELETE FROM order_details WHERE order_id = ?')->execute([$order_id]);
    $pdo->prepare('DELETE FROM orders WHERE id = ?')->execute([$order_id]);
    header('Location: index.php');
    exit;
}

$orders = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll();

include '../admin_header.php';
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Orders</h1>

    <form method="post" class="mb-4">
        <div class="form-row align-items-center">
            <div class="col-auto">
                <label for="default_tax_fee" class="col-form-label">Default Tax Fee:</label>
            </div>
            <div class="col-auto">
                <input type="number" step="0.01" min="0" name="default_tax_fee" id="default_tax_fee" class="form-control mb-2" value="<?= htmlspecialchars($default_tax_fee) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" name="set_tax_fee" class="btn btn-primary mb-2">Set for All Orders</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Order Code</th>
                    <th>User ID</th>
                    <th>Status</th>
                    <th>Subtotal</th>
                    <th>Tax Fee</th>
                    <th>Total</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['order_code']) ?></td>
                    <td><?= $order['user_id'] ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= number_format($order['subtotal'], 2) ?></td>
                    <td><?= number_format($order['tax_fee'], 2) ?></td>
                    <td><?= number_format($order['total'], 2) ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?delete=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</a>
                        <a href="details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../admin_footer.php'; ?>
