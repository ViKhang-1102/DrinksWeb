<?php
include 'header.php';
require_once __DIR__ . '/config/database.php';

// Fetch all categories for validation and display
$cat_stmt = $pdo->query('SELECT id, name FROM categories ORDER BY id ASC');
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
$valid_category_ids = array_map('intval', array_column($categories, 'id'));

$products = [];
$category_valid = false;
if (isset($_GET['category_id']) && $_GET['category_id'] !== '' && in_array((int)$_GET['category_id'], $valid_category_ids)) {
    $category_id = (int)$_GET['category_id'];
    $category_valid = true;
    $stmt = $pdo->prepare('
        SELECT p.*, 
            (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY id ASC LIMIT 1) AS image_url
        FROM products p
        WHERE p.category_id = ?
        ORDER BY p.id DESC
    ');
    $stmt->execute([$category_id]);
    $products = $stmt->fetchAll();
} else {
    // If no valid category selected, show no products
    $products = [];
}
?>

<!--start page content-->
<div class="page-content">

   <!--start breadcrumb-->
   <div class="py-4 border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0"> 
          <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
          <li class="breadcrumb-item"><a href="javascript:;">Shop</a></li>
          <li class="breadcrumb-item active" aria-current="page">Products of the category</li>
        </ol>
      </nav>
    </div>
   </div>
   <!--end breadcrumb-->

   <!--start product grid-->
   <section class="py-4">
    <h5 class="mb-0 fw-bold d-none">Product</h5>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="d-flex align-items-center justify-content-between bg-light p-2 mb-3">
            <div class="product-count"><?= count($products) ?> products</div>
          </div>
        </div>
      </div>
      <style>
      .card.product-card {
        transition: box-shadow 0.2s, transform 0.2s;
      }
      .card.product-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        transform: translateY(-4px) scale(1.03);
        z-index: 2;
      }
      .product-options {
        opacity: 0;
        transition: opacity 0.2s;
      }
      .card.product-card:hover .product-options {
        opacity: 1;
      }
      </style>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php if (empty($products)): ?>
          <div class="col-12"><div class="alert alert-warning">No products!</div></div>
        <?php else: ?>
          <?php foreach ($products as $product): ?>
            <div class="col">
              <div class="card border shadow-none product-card">
                <div class="position-relative overflow-hidden">
                  <div class="product-options d-flex align-items-center justify-content-center gap-2 mx-auto position-absolute bottom-0 start-0 end-0 pb-2">
                    <a href="javascript:;"><i class="bi bi-heart"></i></a>
                    <form method="post" style="display:inline">
                      <input type="hidden" name="add_to_cart_id" value="<?= $product['id'] ?>">
                      <button type="submit" class="btn p-0 border-0 bg-transparent"><i class="bi bi-basket3"></i></button>
                    </form>
                    <a href="product-details.php?id=<?= $product['id'] ?>"><i class="bi bi-zoom-in"></i></a>
                  </div>
                  <a href="product-details.php?id=<?= $product['id'] ?>">
                    <img src="<?= !empty($product['thumbnail']) ? 'assets/images/product-images/' . htmlspecialchars($product['thumbnail']) : 'assets/images/no-image.png' ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="object-fit:cover;min-height:200px;max-height:200px;">
                  </a>
                </div>                
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>
   <!--start product details-->

 </div>
  <!--end page content-->
<?php include 'footer.php'; ?>
