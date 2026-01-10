<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
	header("Location: index.php");
	exit();
}

$username = $_SESSION['admin_username'];
$email = $_SESSION['admin_email'];


include 'config/database.php';

// Query to get total categories
$query = "SELECT COUNT(*) AS total_categories FROM categories";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_categories = $row['total_categories'];


$pro_query = "SELECT COUNT(*) AS total_products FROM products";
$pro_result = mysqli_query($conn, $pro_query);
$pro_row = mysqli_fetch_assoc($pro_result);
$total_products = $pro_row['total_products'];

$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);
$total_users = $user_row['total_users'];

// Total Orders
$order_query = "SELECT COUNT(*) AS total_orders FROM orders";
$order_result = mysqli_query($conn, $order_query);
$order_row = mysqli_fetch_assoc($order_result);
$total_orders = $order_row['total_orders'];

// Total Revenue (Delivered Orders)
$revenue_query = "SELECT IFNULL(SUM(total_amount),0) AS total_revenue FROM orders WHERE status='Delivered'";
$revenue_result = mysqli_query($conn, $revenue_query);
$revenue_row = mysqli_fetch_assoc($revenue_result);
$total_revenue = $revenue_row['total_revenue'];

// Orders by Status
$status_query = "SELECT status, COUNT(*) AS count FROM orders GROUP BY status";
$status_result = mysqli_query($conn, $status_query);

// Initialize with default statuses to ensure all are shown even if count = 0
$orders_by_status = [
    'Pending' => 0,
    'Processing' => 0,
    'Shipped' => 0,
    'Delivered' => 0,
    'Cancelled' => 0
];

while ($row = mysqli_fetch_assoc($status_result)) {
    $orders_by_status[$row['status']] = (int)$row['count']; // ensure integer
}
// Low Stock Products (stock <= 5)
$low_stock_query = "SELECT COUNT(*) AS low_stock FROM products WHERE stock <= 5";
$low_stock_result = mysqli_query($conn, $low_stock_query);
$low_stock_row = mysqli_fetch_assoc($low_stock_result);
$low_stock = $low_stock_row['low_stock'];

// Recent Orders (latest 5)
$recent_orders_query = "
    SELECT o.order_id, u.name AS customer, o.total_amount, o.status, o.order_date
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
    LIMIT 5
";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);

?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>SK Shop</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
		name='viewport' />
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="assets/css/ready.css">
	<link rel="stylesheet" href="assets/css/demo.css">
	<!-- Line Awesome CDN -->
<link href="https://cdn.lineawesome.com/1.3.0/line-awesome/css/line-awesome.min.css" rel="stylesheet">

</head>

<body>
	<div class="wrapper">
		<div class="main-header">
			<div class="logo-header">
				<a href="dashboard.php" class="logo">
					SK Shop Dashboard
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
					data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
			</div>
			<nav class="navbar navbar-header navbar-expand-lg">
				<div class="container-fluid">

					<form class="navbar-left navbar-form nav-search mr-md-3" action="">
						<div class="input-group">
							<input type="text" placeholder="Search ..." class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="la la-search search-icon"></i>
								</span>
							</div>
						</div>
					</form>
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-envelope"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="#">Action</a>
								<a class="dropdown-item" href="#">Another action</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Something else here</a>
							</div>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-bell"></i>
								<span class="notification">3</span>
							</a>
							<ul class="dropdown-menu notif-box" aria-labelledby="navbarDropdown">
								<li>
									<div class="dropdown-title">You have 4 new notification</div>
								</li>
								<li>
									<div class="notif-center">
										<a href="#">
											<div class="notif-icon notif-primary"> <i class="la la-user-plus"></i>
											</div>
											<div class="notif-content">
												<span class="block">
													New user registered
												</span>
												<span class="time">5 minutes ago</span>
											</div>
										</a>
										<a href="#">
											<div class="notif-icon notif-success"> <i class="la la-comment"></i> </div>
											<div class="notif-content">
												<span class="block">
													Rahmad commented on Admin
												</span>
												<span class="time">12 minutes ago</span>
											</div>
										</a>
										<a href="#">
											<div class="notif-img">
												<img src="assets/img/profile2.jpg" alt="Img Profile">
											</div>
											<div class="notif-content">
												<span class="block">
													Reza send messages to you
												</span>
												<span class="time">12 minutes ago</span>
											</div>
										</a>
										<a href="#">
											<div class="notif-icon notif-danger"> <i class="la la-heart"></i> </div>
											<div class="notif-content">
												<span class="block">
													Farrah liked Admin
												</span>
												<span class="time">17 minutes ago</span>
											</div>
										</a>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);"> <strong>See all
											notifications</strong> <i class="la la-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						<li class="nav-item dropdown">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"
								aria-expanded="false">
								<img src="assets/img/profile.jpg" alt="user-img" width="36" class="img-circle">
								<span><?php echo htmlspecialchars($username); ?></span>
							</a>
							<ul class="dropdown-menu dropdown-user">
								<li>
									<div class="user-box">
										<div class="u-img">
											<img src="assets/img/profile.jpg" alt="user">
										</div>
										<div class="u-text">
											<h4><?php echo htmlspecialchars($username); ?></h4>
											<p class="text-muted"><?php echo htmlspecialchars($email); ?></p>
											<a href="profile.html" class="btn btn-rounded btn-danger btn-sm">View
												Profile</a>
										</div>
									</div>
								</li>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#"><i class="ti-user"></i> My Profile</a>
								<a class="dropdown-item" href="#"><i class="ti-wallet"></i> My Balance</a>
								<a class="dropdown-item" href="#"><i class="ti-email"></i> Inbox</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#"><i class="ti-settings"></i> Account Setting</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
							</ul>
							<!-- /.dropdown-user -->
						</li>
					</ul>
				</div>
			</nav>
		</div>
		<div class="sidebar">
			<div class="scrollbar-inner sidebar-wrapper">
				<div class="user">
					<div class="photo">
						<img src="assets/img/profile.jpg">
					</div>
					<div class="info">
						<a class="" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
							<span>
								<?php echo htmlspecialchars($username); ?>
								<span class="user-level">Administrator</span>
								<span class="caret"></span>
							</span>
						</a>
						<div class="clearfix"></div>

						<div class="collapse in" id="collapseExample" aria-expanded="true" style="">
							<ul class="nav">
								<li>
									<a href="#profile">
										<span class="link-collapse">My Profile</span>
									</a>
								</li>
								<li>
									<a href="#edit">
										<span class="link-collapse">Edit Profile</span>
									</a>
								</li>
								<li>
									<a href="#settings">
										<span class="link-collapse">Settings</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<ul class="nav">
					<li class="nav-item active">
						<a href="dashboard.php">
							<i class="la la-dashboard"></i>
							<p>Dashboard</p>
							<span class="badge badge-count">5</span>
						</a>
					</li>

					<li class="nav-item active">
						<a href="user_management.php">
							<i class="la la-user"></i>
							<p>User Management</p>

						</a>
					</li>

					<li class="nav-item active">
						<a href="category_management.php">
							<i class="la la-list"></i>
							<p>Category Management</p>

						</a>
					</li>


					<li class="nav-item active">
						<a href="product_management.php">
							<i class="la la-cube "></i>
							<p>Products Management</p>

						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<div class="content">
				<div class="container-fluid">
					<h4 class="page-title">Dashboard</h4>
					<div class="row">

						<div class="col-md-3">
							<div class="card card-stats">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center icon-warning">
												<i class="la la-user text-warning"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">User</p>
												<h4 class="card-title"><?php echo $total_users; ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card card-stats">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-list text-success"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Category</p>
												<h4 class="card-title"><?php echo $total_categories; ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card card-stats">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-cube text-danger"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Products</p>
												<h4 class="card-title"><?php echo $total_products; ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
    <div class="card card-stats">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <div class="icon-big text-center">
                        <i class="la la-shopping-cart text-primary"></i>
                    </div>
                </div>
                <div class="col-7 d-flex align-items-center">
                    <div class="numbers">
                        <p class="card-category">Total Orders</p>
                        <h4 class="card-title"><?php echo $total_orders; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card card-stats">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <div class="icon-big text-center">
                        <i class="la la-dollar text-success"></i>
                    </div>
                </div>
                <div class="col-7 d-flex align-items-center">
                    <div class="numbers">
                        <p class="card-category">Revenue</p>
                        <h4 class="card-title">$<?php echo number_format($total_revenue,2); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card card-stats">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <div class="icon-big text-center">
                        <i class="la la-exclamation-triangle text-danger"></i>
                    </div>
                </div>
                <div class="col-7 d-flex align-items-center">
                    <div class="numbers">
                        <p class="card-category">Low Stock</p>
                        <h4 class="card-title"><?php echo $low_stock; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
	.chart-legend {
    display: flex;
    justify-content: center;
    margin-top: 10px;
    flex-wrap: wrap;
    gap: 15px;
}

.chart-legend-item {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #333;
}

.chart-legend-color {
    width: 16px;
    height: 16px;
    margin-right: 6px;
    border-radius: 3px;
}

</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Orders by Status</h4>
        </div>
        <div class="card-body">
            <!-- Give a unique ID for chart instead of class for safety -->
           <div id="orders-status-chart" style="height:300px; max-width:500px; margin:0 auto;" class="ct-chart ct-perfect-fourth"></div>
<div id="orders-status-legend" class="chart-legend"></div>


        </div>
    </div>
</div>




<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Recent Orders</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($recent_orders_result)) { ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer']); ?></td>
                            <td>$<?php echo number_format($order['total_amount'],2); ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo date("d-m-Y H:i", strtotime($order['order_date'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

					</div>

				</div>
			</div>

		</div>
	</div>
	</div>

</body>
<script src="assets/js/core/jquery.3.2.1.min.js"></script>
<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/chartist/chartist.min.js"></script>
<script src="assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/maps/world_countries.min.js"></script>
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/ready.min.js"></script>
<script src="assets/js/demo.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    var seriesData = [
        <?php echo $orders_by_status['Pending']; ?>,
        <?php echo $orders_by_status['Processing']; ?>,
        <?php echo $orders_by_status['Shipped']; ?>,
        <?php echo $orders_by_status['Delivered']; ?>,
        <?php echo $orders_by_status['Cancelled']; ?>
    ];

    // Define your colors for the slices (make sure same order as statuses)
   var colors = [
    '#007bff', // Pending - Blue
    '#fd7e14', // Processing - Orange
    '#ffc107', // Shipped - Yellow
    '#28a745', // Delivered - Green
    '#dc3545'  // Cancelled - Red
];


    var data = {
        series: seriesData,
        labels: statuses
    };

    var chart = new Chartist.Pie('#orders-status-chart', data, {
        labelInterpolationFnc: function(value, idx) {
            var total = seriesData.reduce(function(a, b) { return a + b; }, 0);
            if ((seriesData[idx] / total) > 0.1) {
                return value;
            } else {
                return '';
            }
        },
        plugins: [Chartist.plugins.tooltip()]
    });

    // Apply the colors to slices on draw event
    chart.on('draw', function(ctx) {
        if(ctx.type === 'slice') {
            ctx.element.attr({
                style: 'fill: ' + colors[ctx.index]
            });
        }
    });

    // Build custom legend
    var legendContainer = document.getElementById('orders-status-legend');
    legendContainer.innerHTML = ''; // clear previous

    statuses.forEach(function(status, i) {
        var legendItem = document.createElement('div');
        legendItem.className = 'chart-legend-item';

        var colorBox = document.createElement('span');
        colorBox.className = 'chart-legend-color';
        colorBox.style.backgroundColor = colors[i];

        var label = document.createTextNode(status);

        legendItem.appendChild(colorBox);
        legendItem.appendChild(label);

        legendContainer.appendChild(legendItem);
    });
});


</script>



</html>