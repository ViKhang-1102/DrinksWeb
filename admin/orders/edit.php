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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? $order['status'];
    $note = $_POST['note'] ?? $order['note'];
    $tax_fee = isset($_POST['tax_fee']) ? floatval($_POST['tax_fee']) : $order['tax_fee'];
    $total = $order['subtotal'] + $tax_fee;
    $stmt = $pdo->prepare('UPDATE orders SET status = ?, note = ?, tax_fee = ?, total = ? WHERE id = ?');
    $stmt->execute([$status, $note, $tax_fee, $total, $order_id]);
    header('Location: index.php');
    exit;
}

include '../admin_header.php';
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Order #<?= $order['id'] ?></h1>
    <form method="post" class="col-md-6">
        <div class="form-group">
            <label>Order Code</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($order['order_code']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>User ID</label>
            <input type="text" class="form-control" value="<?= $order['user_id'] ?>" disabled>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <?php foreach (["pending", "processing", "completed", "cancelled"] as $st): ?>
                    <option value="<?= $st ?>" <?= $order['status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Note</label>
            <textarea name="note" class="form-control" rows="3"><?= htmlspecialchars($order['note']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Tax Fee</label>
            <input type="number" step="0.01" min="0" name="tax_fee" class="form-control" value="<?= $order['tax_fee'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include '../admin_footer.php'; ?>
