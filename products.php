<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KidsZone - All Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">

</head>

<body>

  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="bi bi-balloon-heart-fill"></i> KidsZone</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2">

          <li class="nav-item">
            <a class="nav-link " href="index.php">
              <i class="bi bi-house-door"></i> Home
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="about.php">
              <i class="bi bi-heart"></i> About
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="products.php">
              <i class="bi bi-grid-3x3-gap-fill"></i> Shop
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link position-relative" href="cart.php">
              <i class="bi bi-cart3"></i> Cart
              <span id="cartCountNav" class="cart-badge">0</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="order.php">
              <i class="bi bi-bag-check-fill"></i> My Orders
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="contact.php">
              <i class="bi bi-telephone"></i> Contact Us
            </a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i>

              <?php if (isset($_SESSION['user_name'])): ?>
                <?= $_SESSION['user_name']; ?>
              <?php else: ?>
                Account
              <?php endif; ?>

            </a>

            <ul class="dropdown-menu dropdown-menu-end">

              <?php if (!isset($_SESSION['user_name'])): ?>

                <li>
                  <a class="dropdown-item" href="login.php">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                  </a>
                </li>

                <li>
                  <a class="dropdown-item" href="signup.php">
                    <i class="bi bi-person-plus"></i> Signup
                  </a>
                </li>

              <?php else: ?>

                <li>
                  <span class="dropdown-item-text">
                    👋 Hello,
                    <?= $_SESSION['user_name']; ?>
                  </span>
                </li>

                <li>
                  <hr class="dropdown-divider">
                </li>

                <li>
                  <a class="dropdown-item text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                  </a>
                </li>

              <?php endif; ?>

            </ul>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  <main class="container my-4">
    <h1 class="fw-bold text-center mb-2">✨ All Toys & Games ✨</h1>
    <p class="text-center mb-4">Spark imagination with our magical collection</p>

    <!-- Filter Buttons -->
    <div class="text-center mb-4">

      <div class="d-flex flex-wrap justify-content-center gap-2" id="categoryFilters">

      </div>

    </div>

    </div>

    <!-- Products Grid -->
    <div class="row g-4" id="productsGrid"></div>

    <!-- No Results Message -->
    <div id="noResults" class="text-center py-5 d-none">
      <i class="bi bi-emoji-frown fs-1 text-warning"></i>
      <h4 class="mt-3">No toys found in this category!</h4>
      <p>Try another category or check back soon for new arrivals 🎈</p>
      <button class="btn btn-kid mt-2" onclick="filterProducts('all')">View All Products</button>
    </div>
  </main>

  <footer class="text-center">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-3">
          <h5><i class="bi bi-balloon-heart-fill"></i> KidsZone</h5>
          <p>Where imagination grows! Safe, fun, educational toys for ages 0-10.</p>
        </div>
        <div class="col-md-4 mb-3">
          <h5>Quick Links</h5>
          <p><a href="about.php" class="text-decoration-none text-dark">About Us</a> | <a href="products.php"
              class="text-decoration-none text-dark">Shop</a> | <a href="cart.php"
              class="text-decoration-none text-dark">Cart</a></p>
        </div>
        <div class="col-md-4 mb-3">
          <h5>Follow the Fun</h5>
          <i class="bi bi-instagram fs-3 mx-2"></i>
          <i class="bi bi-facebook fs-3 mx-2"></i>
          <i class="bi bi-youtube fs-3 mx-2"></i>
        </div>
      </div>
      <hr class="bg-dark">
      <p class="mb-0">🌟 KidsZone — Where imagination grows! 🌟 <br>© 2025 Magical Toy Store — Safe, Fun, Creative</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>

    let products = [];



    let urlParams = new URLSearchParams(window.location.search);
    let currentCategory = urlParams.get('category') || 'all';



    /* =========================
       LOAD CATEGORIES
    ========================= */
    async function loadCategories() {

      try {

        let response = await fetch('api/category.php');

        let categories = await response.json();

        let html = `

      <button class="btn-filter active"
          data-category="all">

          <img src="admin/assets/img/category/storage_box.png"
              class="filter-icon">

          All Products

      </button>

    `;

        categories.forEach(category => {

          html += `
        <button class="btn-filter"
            data-category="${category.id}">

            <img src="admin/assets/img/category/${category.icon}"
                class="filter-icon">

            ${category.name}

        </button>
      `;
        });

        document.getElementById('categoryFilters').innerHTML = html;

        document.querySelectorAll('.btn-filter').forEach(btn => {

          if (btn.dataset.category == currentCategory) {
            btn.classList.add('active');
          } else {
            btn.classList.remove('active');
          }

          btn.addEventListener('click', () => {
            filterProducts(btn.dataset.category);
          });

        });

      } catch (error) {

        console.log(error);

      }
    }

    /* =========================
       LOAD PRODUCTS
    ========================= */
    /* =========================
       LOAD PRODUCTS
    ========================= */
    async function loadProducts(category = 'all') {

      try {

        // if sale clicked -> load all products
        let apiCategory = (category === 'sale')
          ? 'all'
          : category;

        let response = await fetch(
          `api/products.php?category=${apiCategory}`
        );

        products = await response.json();

        renderProducts(products);

      } catch (error) {

        console.log(error);

      }
    }

    /* =========================
       RENDER PRODUCTS
    ========================= */
    function renderProducts(productsData) {

      const grid = document.getElementById('productsGrid');

      const noResults = document.getElementById('noResults');

      if (!productsData.length) {

        grid.innerHTML = '';

        noResults.classList.remove('d-none');

        return;
      }

      noResults.classList.add('d-none');

      grid.innerHTML = productsData.map(product => {

        const stock = Number(product.stock || 0);

        return `

      <div class="col-md-6 col-lg-3">

        <div class="card card-hover h-100">

          <img src="${product.image}"
              class="product-img"
              alt="${product.name}"
              onclick="viewDetails(${product.id})">

          <div class="card-body text-center">

            <h5 class="card-title fw-bold"
                style="cursor:pointer;"
                onclick="viewDetails(${product.id})">

              ${product.name}

            </h5>

            <div class="rating mb-2">
              ${renderStars(product.rating || 0)}
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">

  <p class="price mb-0">
    ₹${Number(product.price).toFixed(0)}
  </p>

  ${stock === 0
            ? `
      <span class="badge bg-danger">
        Out of Stock
      </span>
    `
            : stock <= 5
              ? `
      <span class="badge bg-danger">
        Only ${stock} left
      </span>
    `
              : `
      <span class="badge bg-success">
        In Stock
      </span>
    `
          }

</div>

            <div class="d-flex gap-2">

              <button
                class="btn btn-kid flex-grow-1"
                ${stock === 0 ? 'disabled' : ''}
                onclick="addToCart(
  ${product.id},
  '${String(product.name).replace(/'/g, "\\'")}',
  ${product.price},
  '${product.image}',
  ${stock}
)">

                <i class="bi bi-cart-plus"></i>

                ${stock === 0 ? 'Sold Out' : 'Add'}

              </button>

              <button
                class="btn btn-details"
                onclick="viewDetails(${product.id})">

                <i class="bi bi-eye"></i> View

              </button>

            </div>

          </div>

        </div>

      </div>

    `;

      }).join('');
    }

    /* =========================
       STAR RENDER
    ========================= */
    function renderStars(rating = 0) {

      let stars = '';

      for (let i = 1; i <= 5; i++) {

        if (i <= rating) {

          stars += '<i class="bi bi-star-fill"></i>';

        } else {

          stars += '<i class="bi bi-star"></i>';
        }
      }

      return stars;
    }

    /* =========================
       FILTER PRODUCTS
    ========================= */
    function filterProducts(category) {

      currentCategory = category;

      // update URL without reload (optional but better UX)
      window.history.pushState({}, "", `products.php?category=${category}`);

      document.querySelectorAll('.btn-filter').forEach(btn => {

        if (btn.dataset.category === category) {
          btn.classList.add('active');
        } else {
          btn.classList.remove('active');
        }

      });

      loadProducts(category);
    }

    /* =========================
       UPDATE BADGE
    ========================= */
    async function updateCartBadge() {

      let badge = document.getElementById('cartCountNav');

      <?php if (isset($_SESSION['user_id'])): ?>

      // LOGGED IN USER → DATABASE

      try {

        let response = await fetch(
          'api/cart_count.php'
        );

        let data = await response.json();

        badge.innerText = data.count;

      } catch (error) {

        console.log(error);

      }

      <?php else: ?>

      badge.innerText = 0;

      <?php endif; ?>

    }

    /* =========================
       ADD TO CART
    ========================= */
    window.addToCart = async (
  id,
  name,
  price,
  img,
  stock
) => {

  if(stock <= 0){
    showToast('Out of stock');
    return;
  }

  <?php if(isset($_SESSION['user_id'])): ?>

    // LOGIN USER -> DATABASE CART

    try {

      let response = await fetch(
        'api/add_to_cart.php',
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            product_id: id,
            qty: 1
          })
        }
      );

      let data = await response.json();

      if(data.status === 'success') {

        updateCartBadge();

        showToast(`✨ ${name} added to cart!`);

      } else {

        showToast(data.message || 'Cart error');

      }

    } catch(error) {

      console.log(error);

    }

  <?php else: ?>

    // NOT LOGIN -> LOGIN PAGE

    window.location.href =
      'login.php?redirect=products.php';

  <?php endif; ?>

};
    /* =========================
       VIEW DETAILS
    ========================= */
   window.viewDetails = (id) => {
  window.location.href =
    `products_details.php?id=${id}`;
};

    /* =========================
       TOAST
    ========================= */
    function showToast(message) {

      const msg = document.createElement('div');

      msg.className =
        'position-fixed bottom-0 end-0 p-3 bg-success text-white rounded-4 m-3';

      msg.style.zIndex = '9999';

      msg.innerHTML = message;

      document.body.appendChild(msg);

      setTimeout(() => {

        msg.remove();

      }, 2000);
    }

    /* =========================
       INITIAL LOAD
    ========================= */
    loadCategories();

    loadProducts(currentCategory);

    updateCartBadge();

  </script>
</body>

</html>