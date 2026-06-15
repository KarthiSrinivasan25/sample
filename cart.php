<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KidsZone - Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <link href="assets/css/styles.css" rel="stylesheet">
  <style>




  </style>
</head>

<body>
  <?php
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=cart.php");
    exit;
  }
  ?>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="bi bi-balloon-heart-fill"></i> KidsZone</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2">
          <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Home</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php"><i class="bi bi-heart"></i> About</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php"><i class="bi bi-grid-3x3-gap-fill"></i> Shop</a>
          </li>
          <li class="nav-item"><a class="nav-link active position-relative" href="cart.php"><i class="bi bi-cart3"></i>
              Cart <span id="cartCountNav" class="cart-badge">0</span></a></li>
          <li class="nav-item">
            <a class="nav-link" href="order.php">
              <i class="bi bi-bag-check-fill"></i> My Orders
            </a>
          </li>
          <li class="nav-item"><a class="nav-link" href="contact.php"><i class="bi bi-telephone"></i> Contact Us</a>
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
    <h1 class="fw-bold mb-4" data-aos="fade-up">🛒 Your Toy Cart</h1>

    <div class="row g-4">
      <!-- Cart Items Column -->
      <div class="col-lg-8">
        <div class="cart-summary" data-aos="fade-right">
          <div id="cartContainer"></div>
        </div>

        <!-- Coupon Section -->
        <div class="coupon-section mt-3" id="couponSection" data-aos="fade-up">
          <div class="row align-items-center">
            <div class="col-md-7">
              <i class="bi bi-ticket-perforated fs-4 text-warning"></i>
              <span class="fw-bold ms-2">Have a coupon code?</span>
            </div>
            <div class="col-md-5">
              <div class="input-group">
                <input type="text" class="coupon-input form-control" id="couponCode" placeholder="Enter code">
                <button class="btn btn-kid" onclick="applyCoupon()">Apply</button>
              </div>
            </div>
          </div>
          <div id="couponMessage" class="small mt-2 text-success"></div>
        </div>
      </div>

      <!-- Order Summary Column -->
      <div class="col-lg-4" data-aos="fade-left">
        <div class="order-summary " id="orderSummaryBox">
          <h5 class="fw-bold mb-3"><i class="bi bi-receipt"></i> Order Summary</h5>
          <div id="summaryDetails"></div>
          <div class="mt-3">


            <?php if (isset($_SESSION['user_id'])) { ?>

              <a href="checkout.php" id="checkoutBtn" class="btn btn-kid w-100 py-3">

                Proceed to Checkout →

              </a>

            <?php } else { ?>

              <a href="login.php?redirect=cart.php" id="checkoutBtn" class="btn btn-kid w-100 py-3">

                Proceed to Checkout →

              </a>

            <?php } ?>
          </div>
          <!-- <div class="text-center mt-3">
            <small class="text-muted">
              <i class="bi bi-shield-check"></i> Secure checkout<br>
              <i class="bi bi-truck"></i> Free shipping on orders $50+
            </small>
          </div> -->
        </div>

        <!-- Recommended Products
        <div class="mt-4 p-3 bg-white rounded-4 shadow-sm" data-aos="fade-up">
          <h6 class="fw-bold mb-2"><i class="bi bi-stars"></i> You might also like</h6>
          <div class="row g-2" id="recommendedProducts"></div>
        </div> -->
      </div>
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
              class="text-decoration-none text-dark">Cart</a> | <a href="contact.php"
              class="text-decoration-none text-dark">Contact</a></p>
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
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 600, once: true });

    let cart = [];

    let products = [];

    let appliedCoupon = null;
    let couponDiscount = 0;

    /* =========================
       LOAD PRODUCTS
    ========================= */
    async function loadProducts() {

      try {

        const res = await fetch(
          "api/products.php?category=all"
        );

        products = await res.json();

        <?php if (isset($_SESSION['user_id'])): ?>

          // =========================
          // LOAD DATABASE CART
          // =========================

          const cartRes = await fetch(
            'api/get_cart.php'
          );

          const dbCart = await cartRes.json();

          cart = dbCart.map(item => ({
            id: item.id,
            name: item.product_name,
            price: Number(item.price),
            img: 'admin/assets/img/product/' + item.image,
            qty: Number(item.qty)
          }));

          console.log(cart);
          console.log(products);
        <?php endif; ?>

        // STOCK VALIDATION
        cart.forEach(item => {

          const stock = getProductStock(item.id);

          if (item.qty > stock) {

            item.qty = stock;
          }

        });

        // REMOVE OUT OF STOCK ITEMS
        cart = cart.filter(item => {

          return getProductStock(item.id) > 0;

        });

        renderCart();

        updateSummary();

        updateCartBadge();


      } catch (err) {

        console.log(err);

        renderCart();

      }
    }

    /* =========================
       GET PRODUCT STOCK
    ========================= */
    function getProductStock(id) {

      const product = products.find(
        p =>
          Number(p.id || p.product_id) === Number(id)
      );

      return Number(product?.stock || 0);
    }
    /* =========================
       SAVE CART
    ========================= */
    async function saveCart() {

      for (const item of cart) {

        await fetch('api/update_cart.php', {

          method: 'POST',

          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },

          body:
            'product_id=' + item.id +
            '&qty=' + item.qty

        });

      }

      renderCart();
      updateSummary();
      updateCartBadge();
    }

    /* =========================
       CART BADGE
    ========================= */
    async function updateCartBadge() {

      try {

        let response =
          await fetch('api/cart_count.php');

        let data =
          await response.json();

        let badge =
          document.getElementById('cartCountNav');

        if (badge) {

          badge.innerText = data.count;
        }

      } catch (error) {

        console.log(error);

      }
    }
    /* =========================
       CART SUBTOTAL
    ========================= */
    function getCartSubtotal() {

      return cart.reduce((sum, item) => {

        return sum +
          (
            Number(item.price || 0)
            *
            Number(item.qty || 1)
          );

      }, 0);
    }





    function toggleSummarySections() {
  const coupon = document.getElementById('couponSection');
  const summary = document.getElementById('orderSummaryBox');

  if (!cart || cart.length === 0) {
    coupon.style.display = "none";
    summary.style.display = "none";
  } else {
    coupon.style.display = "block";
    summary.style.display = "block";
  }
}


    /* =========================
       RENDER CART
    ========================= */
    function renderCart() {

      const container =
        document.getElementById('cartContainer');

      /* EMPTY CART */
      if (!cart.length) {

        container.innerHTML = `
      <div class="empty-cart">
        <i class="bi bi-cart-x"></i>

        <h4>Your cart is empty</h4>

        <p>Add some fun toys to continue shopping!</p>

        <a href="products.php"
          class="btn btn-kid mt-3">

          Start Shopping

        </a>
      </div>
    `;
toggleSummarySections();
        return;
      }

      let html = `
    <div class="table-responsive">
      <table class="table table-cart align-middle">

        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th width="200">Quantity</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
  `;

      cart.forEach((item) => {

        const id = Number(item.id);

        const name = item.name || 'Toy';

        const img =
          item.img ||
          item.image ||
          'https://via.placeholder.com/80';

        const price = Number(item.price || 0);

        const qty = Number(item.qty || 1);

        const subtotal = price * qty;

        const stock = getProductStock(id);

        let stockText = '';

        if (stock <= 0) {

          stockText =
            `<small class="text-danger fw-bold">
          Out of Stock
        </small>`;

        } else if (stock < 5) {

          stockText =
            `<small class="text-danger fw-bold">
          Only ${stock} left
        </small>`;

        } else {

          stockText =
            `<small class="text-success">
          In Stock
        </small>`;
        }

        html += `
      <tr>

        <td>
          <div class="d-flex align-items-center gap-3">

            <img src="${img}"
              style="
                width:70px;
                height:70px;
                object-fit:cover;
                border-radius:18px;
                background:#fff3e6;
                padding:8px;
              ">

            <div>

              <h6 class="fw-bold mb-1">
                ${name}
              </h6>

              ${stockText}

            </div>

          </div>
        </td>

        <td class="fw-bold">
          ₹${price.toFixed(0)}
        </td>

        <td>

          <div class="d-flex align-items-center gap-2">

            <button
              class="btn btn-sm btn-outline-secondary rounded-circle"

              onclick="changeQty(${id}, -1)"

              style="width:32px;height:32px;">

              -

            </button>

            <input
              type="number"

              min="1"

              max="${stock}"

              value="${qty}"

              class="quantity-input"

              onchange="setQty(${id}, this.value)">

            <button
              class="btn btn-sm btn-outline-secondary rounded-circle"

              onclick="changeQty(${id}, 1)"

              style="width:32px;height:32px;">

              +

            </button>

          </div>

        </td>

        <td class="fw-bold text-success">
          ₹${subtotal.toFixed(0)}
        </td>

        <td>

          <button
            class="btn btn-sm btn-outline-danger rounded-circle"

            onclick="removeItem(${id})"

            style="width:34px;height:34px;">

            <i class="bi bi-trash"></i>

          </button>

        </td>

      </tr>
    `;
      });

      html += `
        </tbody>
      </table>
    </div>
  `;

      container.innerHTML = html;

      toggleSummarySections();
    }

    /* =========================
       CHANGE QTY
    ========================= */
    window.changeQty = (id, change) => {

      const item = cart.find(
        i => Number(i.id) === Number(id)
      );

      if (!item) return;

      const stock = getProductStock(id);

      let newQty =
        Number(item.qty || 1) + change;

      if (newQty < 1) newQty = 1;

      if (newQty > stock) {

        alert(`Only ${stock} items available`);

        newQty = stock;
      }

      item.qty = newQty;

      saveCart();
    };

    /* =========================
       MANUAL INPUT
    ========================= */
    window.setQty = (id, value) => {

      const stock = getProductStock(id);

      let qty = parseInt(value);

      if (isNaN(qty) || qty < 1)
        qty = 1;

      if (qty > stock) {

        alert(`Only ${stock} items available`);

        qty = stock;
      }

      const item = cart.find(
        i => Number(i.id) === Number(id)
      );

      if (!item) return;

      item.qty = qty;

      saveCart();
    };

    /* =========================
       REMOVE ITEM
    ========================= */
    window.removeItem = async (id) => {

      await fetch('api/remove_cart.php', {

        method: 'POST',

        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },

        body: 'product_id=' + id

      });

      cart = cart.filter(
        item => Number(item.id) !== Number(id)
      );

      renderCart();
      updateSummary();
      updateCartBadge();

    };

    /* =========================
       ORDER SUMMARY
    ========================= */
    function updateSummary() {

      const summary =
        document.getElementById('summaryDetails');

      const subtotal =
        getCartSubtotal();

      /* FREE SHIPPING ₹999+ */
      let shipping = 40;

      if (subtotal > 200) {
        shipping = 0;
      }

      const tax = Math.round(subtotal * 0.08);

      let total =
        subtotal +
        shipping +
        tax -
        couponDiscount;

      if (total < 0) total = 0;

      summary.innerHTML = `

    <div class="summary-row">
      <span>Subtotal</span>

      <span class="fw-bold">
        ₹${subtotal.toFixed(0)}
      </span>
    </div>

    <div class="summary-row">

      <span>Shipping</span>

      <span>

        ${shipping === 0
          ? '<span class="text-success">Free</span>'
          : '₹' + shipping.toFixed(0)
        }

      </span>

    </div>

    <div class="summary-row">

      <span>Tax (8%)</span>

      <span>
        ₹${tax.toFixed(0)}
      </span>

    </div>

    ${couponDiscount > 0
          ? `
      <div class="summary-row text-success">

        <span>Discount</span>

        <span>
          -₹${couponDiscount.toFixed(0)}
        </span>

      </div>
      `
          : ''
        }

    <div class="summary-row summary-total mt-2 pt-2"
      style="border-bottom:none;">

      <span>Total</span>

      <span>
        ₹${total.toFixed(0)}
      </span>

    </div>
  `;
    }

    /* =========================
       APPLY COUPON
    ========================= */
    window.applyCoupon = () => {

      const input = document.getElementById('couponCode');
      const msg = document.getElementById('couponMessage');

      const code = input.value.trim().toUpperCase();
      const subtotal = getCartSubtotal();

      const coupons = {
        WELCOME10: { discount: 0.10, min: 0 },
        KIDSZONE15: { discount: 0.15, min: 500 },
        TOYFUN20: { discount: 0.20, min: 1000 }
      };

      if (coupons[code]) {

        if (subtotal < coupons[code].min) {

          couponDiscount = 0;

          msg.innerHTML = `Minimum order ₹${coupons[code].min} required`;
          msg.style.color = 'red';

          updateSummary();
          return;
        }

        appliedCoupon = code;

        couponDiscount = subtotal * coupons[code].discount;

        // SAVE COUPON IN SESSION
        fetch('api/save_coupon.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body:
            'coupon=' + encodeURIComponent(code) +
            '&discount=' + encodeURIComponent(couponDiscount)
        });

        msg.innerHTML = `Coupon Applied: ${code}`;
        msg.style.color = 'green';

      } else {

        couponDiscount = 0;

        // REMOVE COUPON FROM SESSION
        fetch('api/save_coupon.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body:
            'coupon=' +
            '&discount=0'
        });

        msg.innerHTML = 'Invalid Coupon Code';
        msg.style.color = 'red';
      }

      updateSummary();
    };

    /* =========================
       INIT
    ========================= */
    loadProducts();

  </script>
</body>

</html>