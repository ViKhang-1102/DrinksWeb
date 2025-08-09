<!--start footer-->
<section class="footer-section bg-section-2 section-padding">
  <div class="container">
    <div class="row row-cols-1 row-cols-lg-4 g-4">
      <div class="col">
        <div class="footer-widget-6">
          <img src="assets/images/logo.webp" class="logo-img mb-3" alt="">
          <h5 class="mb-3 fw-bold">About Us</h5>
          <p class="mb-2">We bring you the finest drinks—from fresh juices and soft drinks to rich coffees and artisan teas—crafted for every taste and occasion, with quality in every sip.</p>

          <a class="link-dark" href="javascript:;">Read More</a>
        </div>
      </div>
      <div class="col">
        <div class="footer-widget-7">
          <h5 class="mb-3 fw-bold">Explore</h5>
          <ul class="widget-link list-unstyled">
            <li><a href="javascript:;">All Drinks</a></li>
            <li><a href="javascript:;">Juice</a></li>
            <li><a href="javascript:;">Soft drinks</a></li>
            <li><a href="javascript:;">Coffee</a></li>
          </ul>
        </div>
      </div>
      <div class="col">
        <div class="footer-widget-8">
          <h5 class="mb-3 fw-bold">Company</h5>
          <ul class="widget-link list-unstyled">
            <li><a href="javascript:;">About Us</a></li>
            <li><a href="javascript:;">Contact Us</a></li>
            <li><a href="javascript:;">FAQ</a></li>
            <li><a href="javascript:;">Privacy</a></li>
            <li><a href="javascript:;">Terms</a></li>
            <li><a href="javascript:;">Complaints</a></li>
          </ul>
        </div>
      </div>
      <div class="col">
        <div class="footer-widget-9">
          <h5 class="mb-3 fw-bold">Follow Us</h5>
          <div class="social-link d-flex align-items-center gap-2">
            <a href="javascript:;"><i class="bi bi-facebook"></i></a>
            <a href="javascript:;"><i class="bi bi-twitter"></i></a>
            <a href="javascript:;"><i class="bi bi-linkedin"></i></a>
            <a href="javascript:;"><i class="bi bi-youtube"></i></a>
            <a href="javascript:;"><i class="bi bi-instagram"></i></a>
          </div>
          <div class="mb-4 mt-4">
            <h5 class="mb-0 fw-bold">Support</h5>
            <p class="mb-0 text-muted">admin@example.com</p>
          </div>
          <div class="">
            <h5 class="mb-0 fw-bold">Toll Free</h5>
            <p class="mb-0 text-muted">(+84)123-456-789</p>
          </div>
        </div>
      </div>
    </div><!--end row-->
    <div class="my-5"></div>
    <div class="row">
      <div class="col-12">
        <div class="text-center">
          <h5 class="fw-bold mb-3">Download Mobile App</h5>
        </div>
        <div class="app-icon d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2">
          <div>
            <a href="javascript:;">
              <img src="assets/images/play-store.webp" width="160" alt="">
            </a>
          </div>
          <div>
            <a href="javascript:;">
              <img src="assets/images/apple-store.webp" width="160" alt="">
            </a>
          </div>
        </div>
      </div>
    </div><!--end row-->

  </div>
</section>
<!--end footer-->

  </div>
  <!--end cat-->
<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class="bi bi-arrow-up"></i></a>
<!--End Back To Top Button-->

<!-- JavaScript files -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="assets/plugins/slick/slick.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/index.js"></script>
<script src="assets/js/loader.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['logout_success'])): ?>
<script>
  toastr.success('<?= $_SESSION['logout_success'] ?>');
</script>
<?php unset($_SESSION['logout_success']); endif; ?>

</body>
</html>