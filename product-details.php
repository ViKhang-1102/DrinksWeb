<?php
session_start();
require_once __DIR__ . '/config/database.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart_id'])) {
    $pid = (int)$_POST['add_to_cart_id'];
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if ($user_id === 0) {
        $_SESSION['cart_success'] = 'You must login to add to cart!';
        header('Location: authentication-login.php');
        exit;
    }
    require_once __DIR__ . '/config/database.php';
    $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ? AND product_id = ?');
    $stmt->execute([$user_id, $pid]);
    if ($stmt->fetch()) {
        $update = $pdo->prepare('UPDATE carts SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?');
        $update->execute([$user_id, $pid]);
    } else {
        $insert = $pdo->prepare('INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, 1)');
        $insert->execute([$user_id, $pid]);
    }
    $_SESSION['cart_success'] = 'Product added to cart!';
    header('Location: product-details.php?id=' . $pid);
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo '<div class="container py-5"><div class="alert alert-danger">No products found!</div></div>';
    include 'footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    echo '<div class="container py-5"><div class="alert alert-danger">No products found!</div></div>';
    include 'footer.php';
    exit;
}

$img_stmt = $pdo->prepare('SELECT image_url FROM product_images WHERE product_id = ?');
$img_stmt->execute([$id]);
$images = $img_stmt->fetchAll();
?>
<section class="py-4 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-xl-7">
                <style>
                .product-images-row {
                  display: flex;
                  gap: 16px;
                  margin-bottom: 1rem;
                }
                .product-images-row .img-thumb-container {
                  flex: 1 1 0;
                  max-width: 50%;
                  height: 500px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  overflow: hidden;
                  border-radius: 8px;
                  background: #f8f9fa;
                }
                .product-images-row img {
                  width: 100%;
                  height: 100%;
                  object-fit: cover;
                  object-position: center;
                  display: block;
                }
                </style>
                <div class="product-images-row">
                  <div class="img-thumb-container" data-fancybox="gallery" data-src="assets/images/product-images/<?= htmlspecialchars($product['thumbnail']) ?>">
                    <img src="assets/images/product-images/<?= htmlspecialchars($product['thumbnail']) ?>" alt="Thumbnail">
                  </div>
                  <?php if (!empty($images[0]['image_url'])): ?>
                  <div class="img-thumb-container" data-fancybox="gallery" data-src="assets/images/product-images/<?= htmlspecialchars($images[0]['image_url']) ?>">
                    <img src="assets/images/product-images/<?= htmlspecialchars($images[0]['image_url']) ?>" alt="Additional Image">
                  </div>
                  <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="product-info">
                    <h4 class="product-title fw-bold mb-1"><?= htmlspecialchars($product['name']) ?></h4>
                    <div class="product-rating">
                        <div class="hstack gap-2 border p-1 mt-3 width-content">
                            <div><span class="rating-number">4.8</span><i class="bi bi-star-fill ms-1 text-warning"></i>
                            </div>
                            <div class="vr"></div>
                            <div>162 Ratings</div>
                        </div>
                    </div>
                    <hr>
                    <div class="product-price d-flex align-items-center gap-3">
                        <div class="h4 fw-bold"><?= number_format($product['price'], 0, ',', '.') ?>₫</div>
                        <div class="h5 fw-light text-muted">Warehouse: <?= $product['stock'] ?></div>
                    </div>
                    <p class="fw-bold mb-0 mt-1 text-success">inclusive of all taxes</p>
                    <div class="cart-buttons mt-3">
                        <form method="post" style="display:inline">
                            <input type="hidden" name="add_to_cart_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="btn btn-lg btn-dark btn-ecomm px-5 py-3 col-lg-6"><i class="bi bi-basket2 me-2"></i>Add to cart</button>
                        </form>
                    </div>
                    <hr class="my-3">
                    <div class="product-info">
                        <h6 class="fw-bold mb-3">Product Details</h6>
                        <p class="mb-1"><?= nl2br(htmlspecialchars($product['descriptions'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</section>
<!--start product details-->

<!--start product details-->
<section class="section-padding">
    <div class="container">
        <div class="separator pb-3">
            <div class="line"></div>
            <h3 class="mb-0 h3 fw-bold">Similar Products</h3>
            <div class="line"></div>
        </div>
        <div class="similar-products">
            <?php
            $allStmt = $pdo->prepare('SELECT * FROM products WHERE category_id = ? AND id != ? ORDER BY created_at DESC');
            $allStmt->execute([$product['category_id'], $product['id']]);
            $allProducts = $allStmt->fetchAll();
            ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-4">
                <?php foreach ($allProducts as $p): ?>
                <div class="col">
                    <a href="product-details.php?id=<?= $p['id'] ?>">
                        <div class="card rounded-0">
                            <img src="assets/images/product-images/<?= htmlspecialchars($p['thumbnail']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="card-img-top rounded-0" style="height:220px;object-fit:cover;">
                            <div class="card-body border-top">
                                <h5 class="mb-0 fw-bold product-short-title" title="<?= htmlspecialchars($p['name']) ?>">
                                    <?= mb_strimwidth(htmlspecialchars($p['name']), 0, 30, '...') ?>
                                </h5>
                                <p class="mb-0 product-short-name">Warehouse: <?= $p['stock'] ?></p>
                                <div class="product-price d-flex align-items-center gap-3 mt-2">
                                    <div class="h6 fw-bold"><?= number_format($p['price'], 0, ',', '.') ?>₫</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <!--end row-->
        </div>
    </div>
</section>
<!--end product details-->

<style>
.similar-products .card {
    transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.2s;
    box-shadow: 0 2px 6px 0 rgb(24 24 24 / 16%), 0 2px 6px 0 rgb(14 14 14 / 39%);
}
.similar-products .card:hover {
    transform: translateY(-6px) scale(1.04);
    box-shadow: 0 8px 24px 0 rgb(24 24 24 / 24%), 0 4px 12px 0 rgb(14 14 14 / 39%);
    z-index: 2;
}
</style>

</div>
<!--end page content-->

<?php include 'footer.php'; ?>
<?php if (!empty($_SESSION['cart_success'])): ?>
      <script>
        toastr.success('<?= $_SESSION['cart_success'] ?>');
      </script>   
      <?php unset($_SESSION['cart_success']); ?>
<?php endif; ?>