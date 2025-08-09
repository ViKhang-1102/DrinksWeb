<!doctype html>
<html lang="en" class="light-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/images/favicon-32x32.webp" type="image/webp" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="assets/plugins/slick/slick-theme.css" />
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/dark-theme.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <title>DrinksWeb</title>
</head>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<body>
    <header class="top-header">
        <nav class="navbar navbar-expand-xl w-100 navbar-dark container gap-3">
            <a class="navbar-brand d-none d-xl-inline" href="index.php"><img src="assets/images/logo.webp"
                    class="logo-img" alt=""></a>
            <a class="mobile-menu-btn d-inline d-xl-none" href="javascript:;" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar">
                <i class="bi bi-list"></i>
            </a>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-header">
                    <div class="offcanvas-logo"><img src="assets/images/logo.webp" class="logo-img" alt="">
                    </div>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body primary-menu">
                    <ul class="navbar-nav justify-content-start flex-grow-1 gap-1">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <?php
                        require_once __DIR__ . '/config/database.php';
                        $stmt = $pdo->query('SELECT c.*, COUNT(p.id) AS product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY product_count DESC LIMIT 10');
                        $categories = $stmt->fetchAll();
                        foreach ($categories as $cat): ?>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="categories.php?category_id=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a>
                        </li>
                        <?php endforeach; ?>
                         <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact-us.php">Contact</a>
                        </li>                        
                    </ul>
                </div>
            </div>
            <ul class="navbar-nav secondary-menu flex-row">
                <li class="nav-item">
                    <a class="nav-link position-relative" href="cart.php">
                        <?php
                        $cart_count = 0;
                        if (!empty($_SESSION['user_id'])) {
                            require_once __DIR__ . '/config/database.php';
                            $stmt = $pdo->prepare('SELECT SUM(quantity) FROM carts WHERE user_id = ?');
                            $stmt->execute([$_SESSION['user_id']]);
                            $cart_count = (int)$stmt->fetchColumn();
                        }
                        ?>
                        <div class="cart-badge"><?php echo $cart_count; ?></div>
                        <i class="bi bi-basket2"></i>
                    </a>
                </li>
                <?php if (!empty($_SESSION['user_id'])): ?>
               
                <li class="nav-item">
                    <a class="nav-link" href="account-profile.php">My Profile</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="authentication-login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="authentication-register.php">Register</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>