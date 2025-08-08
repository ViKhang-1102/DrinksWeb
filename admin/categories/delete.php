<?php
require_once '../../config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $prodStmt = $pdo->prepare('SELECT id FROM products WHERE category_id = :id');
    $prodStmt->execute(['id' => $id]);
    $productIds = $prodStmt->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($productIds)) {
        $in = str_repeat('?,', count($productIds) - 1) . '?';
        $imgStmt = $pdo->prepare("DELETE FROM product_images WHERE product_id IN ($in)");
        $imgStmt->execute($productIds);
        $prodDelStmt = $pdo->prepare("DELETE FROM products WHERE id IN ($in)");
        $prodDelStmt->execute($productIds);
    }
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
    $stmt->execute(['id' => $id]);
    header('Location: index.php?msg=deleted');
    exit;
}
header('Location: index.php');
exit;
?>
