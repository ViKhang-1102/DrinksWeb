<?php
session_start();
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
    header('Location: index.php');
    exit;
}
include 'header.php';
?>

<style>
.carousel-item img {
    width: 100%;
    height: 600px; /* Increased height for larger banners */
    object-fit: cover; /* Ensure images cover the area without distortion */
    object-position: center; /* Center the image */
}
.carousel-item {
    height: 600px; /* Match the container height to the image */
    position: relative; /* Ensure proper positioning */
}
.carousel-inner {
    overflow: visible; /* Prevent clipping of images */
}
.slider-section {
    z-index: 1; /* Ensure carousel is not obscured by other elements */
}
</style>

  <!--page loader-->

  <!--end loader-->

  <!--end top header-->

  <!--start page content-->
  <div class="page-content">
    
    <!--start carousel-->
    <section class="slider-section">
      <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active bg-primary">
            <div class="row d-flex align-items-center">
              <div class="col d-none d-lg-flex justify-content-center">
                <div class="">
                  <h3 class="h3 fw-light text-white fw-bold">Fresh & Healthy Juices</h3>
                  <h1 class="h1 text-white fw-bold">Recharge with <br> pure fresh fruit juice</h1>
                  <p class="text-white fw-bold"><i>Special Offer – Don't Miss Out!</i></p>
                  <div class=""><a class="btn btn-dark btn-ecomm" href="shop-grid.html">Shop Now</a>
                  </div>
                </div>
              </div>
              <div class="col">
                <img src="assets/images/sliders/s_1.webp" class="img-fluid" alt="...">
              </div>
            </div>
          </div>
          <div class="carousel-item bg-red">
            <div class="row d-flex align-items-center">
              <div class="col d-none d-lg-flex justify-content-center">
                <div class="">
                  <h3 class="h3 fw-light text-white fw-bold">Cool Soft Drinks</h3>
                  <h1 class="h1 text-white fw-bold">Great refreshing drink <br> for hot summer days</h1>
                  <p class="text-white fw-bold"><i>Special offer for summer!</i></p>
                  <div class=""> <a class="btn btn-dark btn-ecomm" href="shop-grid.html">Shop Now</a>
                  </div>
                </div>
              </div>
              <div class="col">
                <img src="assets/images/sliders/s_2.webp" class="img-fluid" alt="...">
              </div>
            </div>
          </div>
          <div class="carousel-item bg-purple">
            <div class="row d-flex align-items-center">
              <div class="col d-none d-lg-flex justify-content-center">
                <div class="">
                  <h3 class="h3 fw-light text-white fw-bold">Premium Hot Coffee</h3>
                  <h1 class="h1 text-white fw-bold">Energize your day <br> with a cup of pure coffee</h1>
                  <p class="text-white fw-bold"><i>Enjoy the rich flavor today!</i></p>
                  <div class=""><a class="btn btn-dark btn-ecomm" href="shop-grid.html">Shop Now</a>
                  </div>
                </div>
              </div>
              <div class="col">
                <img src="assets/images/sliders/s_3.webp" class="img-fluid" alt="...">
              </div>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
          data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
          data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </section>
    <!--end carousel-->

    <!--start Featured Products slider-->
    <section class="section-padding">
      <div class="container">
        <div class="text-center pb-3">
          <h3 class="mb-0 h3 fw-bold">Featured Products</h3>
          <p class="mb-0 text-capitalize">The purpose of lorem ipsum</p>
        </div>
        <div class="product-thumbs">
          <?php
          require_once __DIR__ . '/config/database.php';
          $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC LIMIT 8');
          $products = $stmt->fetchAll();
          foreach ($products as $p):
          ?>
          <div class="card">
            <div class="position-relative overflow-hidden">
              <div class="product-options d-flex align-items-center justify-content-center gap-2 mx-auto position-absolute bottom-0 start-0 end-0">
                <a href="javascript:;"><i class="bi bi-heart"></i></a>
                <form method="post" style="display:inline">
                  <input type="hidden" name="add_to_cart_id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn p-0 border-0 bg-transparent"><i class="bi bi-basket3"></i></button>
                </form>
              </div>
              <a href="product-details.php?id=<?= $p['id'] ?>">
                <?php if ($p['thumbnail']): ?>
                  <img src="assets/images/product-images/<?= htmlspecialchars($p['thumbnail']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" style="object-fit:cover; height:220px; width:100%;">
                <?php else: ?>
                  <img src="assets/images/no-image.png" class="card-img-top" alt="No image" style="object-fit:cover; height:220px; width:100%;">
                <?php endif; ?>
              </a>
            </div>
            <div class="card-body">
              <div class="product-info text-center">
                <h6 class="mb-1 fw-bold product-name"><?= htmlspecialchars($p['name']) ?></h6>
                <div class="ratings mb-1 h6">
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                </div>
                <p class="mb-0 h6 fw-bold product-price"><?= number_format($p['price'], 0, ',', '.') ?>₫</p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <!--end Featured Products slider-->

    <!--start tabular product-->
    <section class="product-tab-section section-padding bg-light">
      <div class="container">
        <div class="text-center pb-3">
          <h3 class="mb-0 h3 fw-bold">Explore Latest Drinks</h3>
          <p class="mb-0 text-capitalize">Enjoy every sip — fresh, tasty, and made for you.</p>
        </div>
        <?php
        // Dynamic tabs for categories
        require_once __DIR__ . '/config/database.php';
        $catStmt = $pdo->query('SELECT * FROM categories ORDER BY id ASC');
        $categories = $catStmt->fetchAll();
        ?>
        <div class="row">
          <div class="col-auto mx-auto">
            <div class="product-tab-menu table-responsive">
              <ul class="nav nav-pills flex-nowrap" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all-product" type="button">ALL PRODUCT</button>
                </li>
                <?php foreach ($categories as $cat): ?>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#cat-<?= $cat['id'] ?>" type="button"><?= htmlspecialchars($cat['name']) ?></button>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <hr>
        <div class="tab-content tabular-product">
          <div class="tab-pane fade show active" id="all-product">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-5 g-4">
              <?php
              $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
              $products = $stmt->fetchAll();
              foreach ($products as $p):
              ?>
              <div class="col">
                <div class="card">
                  <div class="position-relative overflow-hidden">
                    <div class="product-options d-flex align-items-center justify-content-center gap-2 mx-auto position-absolute bottom-0 start-0 end-0">
                      <a href="javascript:;"><i class="bi bi-heart"></i></a>
                      <a href="javascript:;"><i class="bi bi-basket3"></i></a>
                    </div>
                    <a href="product-details.php?id=<?= $p['id'] ?>">
                      <?php if ($p['thumbnail']): ?>
                        <img src="assets/images/product-images/<?= htmlspecialchars($p['thumbnail']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" style="object-fit:cover; height:200px; width:100%;">
                      <?php else: ?>
                        <img src="assets/images/no-image.png" class="card-img-top" alt="No image" style="object-fit:cover; height:200px; width:100%;">
                      <?php endif; ?>
                    </a>
                  </div>
                  <div class="card-body">
                    <div class="product-info text-center">
                      <h6 class="mb-1 fw-bold product-name" title="<?= htmlspecialchars($p['name']) ?>">
                        <?= mb_strimwidth(htmlspecialchars($p['name']), 0, 40, '...') ?>
                      </h6>
                      <div class="ratings mb-1 h6">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                      </div>
                      <p class="mb-0 h6 fw-bold product-price"><?= number_format($p['price'], 0, ',', '.') ?>₫</p>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php foreach ($categories as $cat): ?>
          <div class="tab-pane fade" id="cat-<?= $cat['id'] ?>">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-5 g-4">
              <?php
                $stmt = $pdo->prepare('SELECT * FROM products WHERE category_id = ? ORDER BY created_at DESC');
                $stmt->execute([$cat['id']]);
                $products = $stmt->fetchAll();
                foreach ($products as $p):
              ?>
              <div class="col">
                <div class="card">
                  <div class="position-relative overflow-hidden">
                    <div class="product-options d-flex align-items-center justify-content-center gap-2 mx-auto position-absolute bottom-0 start-0 end-0">
                      <a href="javascript:;"><i class="bi bi-heart"></i></a>
                      <a href="javascript:;"><i class="bi bi-basket3"></i></a>
                    </div>
                    <a href="product-details.php?id=<?= $p['id'] ?>">
                      <?php if ($p['thumbnail']): ?>
                        <img src="assets/images/product-images/<?= htmlspecialchars($p['thumbnail']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" style="object-fit:cover; height:200px; width:100%;">
                      <?php else: ?>
                        <img src="assets/images/no-image.png" class="card-img-top" alt="No image" style="object-fit:cover; height:200px; width:100%;">
                      <?php endif; ?>
                    </a>
                  </div>
                  <div class="card-body">
                    <div class="product-info text-center">
                      <h6 class="mb-1 fw-bold product-name" title="<?= htmlspecialchars($p['name']) ?>">
                        <?= mb_strimwidth(htmlspecialchars($p['name']), 0, 40, '...') ?>
                      </h6>
                      <div class="ratings mb-1 h6">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                      </div>
                      <p class="mb-0 h6 fw-bold product-price"><?= number_format($p['price'], 0, ',', '.') ?>₫</p>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <!--end tabular product-->

    <!--start features-->
    <section class="product-thumb-slider section-padding">
      <div class="container">
        <div class="text-center pb-3">
          <h3 class="mb-0 h3 fw-bold">What We Offer!</h3>
          <p class="mb-0 text-capitalize">The Purpose of Refreshing Drinks</p>
        </div>
        <div class="row row-cols-1 row-cols-lg-4 g-4">
          <div class="col d-flex">
            <div class="card depth border-0 rounded-0 border-bottom border-primary border-3 w-100">
              <div class="card-body text-center">
                <div class="h1 fw-bold my-2 text-primary">
                  <i class="bi bi-truck"></i>
                </div>
                <h5 class="fw-bold">Free Delivery</h5>
                <p class="mb-0">Enjoy free delivery on all your favorite drinks. No more hassle—just sip and savor!</p>
              </div>
            </div>
          </div>
          <div class="col d-flex">
            <div class="card depth border-0 rounded-0 border-bottom border-danger border-3 w-100">
              <div class="card-body text-center">
                <div class="h1 fw-bold my-2 text-danger">
                  <i class="bi bi-credit-card"></i>
                </div>
                <h5 class="fw-bold">Secure Payment</h5>
                <p class="mb-0">Pay for your drinks with confidence using our secure payment options. Safe and simple!</p>
              </div>
            </div>
          </div>
          <div class="col d-flex">
            <div class="card depth border-0 rounded-0 border-bottom border-success border-3 w-100">
              <div class="card-body text-center">
                <div class="h1 fw-bold my-2 text-success">
                  <i class="bi bi-minecart-loaded"></i>
                </div>
                <h5 class="fw-bold">Free Returns</h5>
                <p class="mb-0">Not satisfied with your drink? Return it for free—no questions asked!</p>
              </div>
            </div>
          </div>
          <div class="col d-flex">
            <div class="card depth border-0 rounded-0 border-bottom border-warning border-3 w-100">
              <div class="card-body text-center">
                <div class="h1 fw-bold my-2 text-warning">
                  <i class="bi bi-headset"></i>
                </div>
                <h5 class="fw-bold">24/7 Support</h5>
                <p class="mb-0">Our team is here around the clock to assist you with any drink-related queries!</p>
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </section>
    <!--end features-->

    <!--start special product-->
    <section class="section-padding bg-section-2">
      <div class="container">
        <div class="card border-0 rounded-0 p-3 depth">
          <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 text-center">
              <img src="assets/images/extra-images/promo-large.webp" class="img-fluid rounded-0" alt="...">
            </div>
            <div class="col-lg-6">
              <div class="card-body">
                <h3 class="fw-bold">Trending Products</h3>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item bg-transparent px-0">Discover the latest in refreshing beverages!</li>
                  <li class="list-group-item bg-transparent px-0">Contrary to belief, our drinks are carefully crafted, not random mixes.</li>
                  <li class="list-group-item bg-transparent px-0">All our drink recipes website are designed to tantalize your taste buds.</li>
                  <li class="list-group-item bg-transparent px-0">Explore a variety of flavors and blends of our beverages available.</li>
                  <li class="list-group-item bg-transparent px-0">There are many refreshing options to choose from!</li>
                </ul>
                <div class="buttons mt-4 d-flex flex-column flex-lg-row gap-3">
                  <a href="javascript:;" class="btn btn-lg btn-dark btn-ecomm px-5 py-3">Buy Now</a>
                  <a href="javascript:;" class="btn btn-lg btn-outline-dark btn-ecomm px-5 py-3">View Details</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--start special product-->
    
    <!--subscribe banner-->
  <section class="product-thumb-slider subscribe-banner p-5" style="background-image: linear-gradient(to bottom, rgba(150,150,150,0.54), rgba(39,39,39,0.73)), url('assets/images/extra-images/subscribe-bg.webp'); background-size: cover; background-position: center;">
      <div class="row">
        <div class="col-12 col-lg-6 mx-auto">
          <div class="text-center">
            <h3 class="mb-0 fw-bold text-white">Get Latest Update by <br> Subscribe Our Newslater</h3>
            <div class="mt-3">
              <input type="text" class="form-control form-control-lg bubscribe-control rounded-0 px-5 py-3"
                placeholder="Enter your email">
            </div>
            <div class="mt-3 d-grid">
              <button type="button" class="btn btn-lg btn-ecomm bubscribe-button px-5 py-3">Subscribe</button>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--subscribe banner-->
  </div>
  <!--end page content-->

<?php include 'footer.php'; ?>
<?php if (!empty($_SESSION['login_success'])): ?>
      <script>
        toastr.success('<?= $_SESSION['login_success'] ?>');
      </script>   
      <?php unset($_SESSION['login_success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['cart_success'])): ?>
      <script>
        toastr.success('<?= $_SESSION['cart_success'] ?>');
      </script>   
      <?php unset($_SESSION['cart_success']); ?>
<?php endif; ?>