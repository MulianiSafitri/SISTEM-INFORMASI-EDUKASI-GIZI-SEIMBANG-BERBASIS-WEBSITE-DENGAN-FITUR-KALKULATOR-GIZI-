<?php
// admin/index.php
require_once '../inc/config.php';
require_once '../inc/functions.php';
require_once '../inc/admin_auth.php';

if (!is_admin_logged_in()) {
    // Simple inline login for admin
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (login_admin($_POST['username'], $_POST['password'])) {
            redirect('admin/index.php');
        } else {
            $error = "Login gagal.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Admin Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Admin Login</div>
                        <div class="card-body">
                            <?php if (isset($error))
                                echo "<div class='alert alert-danger'>$error</div>"; ?>
                            <form method="POST">
                                <div class="mb-3"><label>Username</label><input type="text" name="username"
                                        class="form-control"></div>
                                <div class="mb-3"><label>Password</label><input type="password" name="password"
                                        class="form-control"></div>
                                <button class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    exit;
}

// Dashboard Logic
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_foods = $pdo->query("SELECT COUNT(*) FROM foods")->fetchColumn();
$total_plans = $pdo->query("SELECT COUNT(*) FROM mealplans")->fetchColumn();

// Chart Data (Calories Distribution)
$cats = $pdo->query("SELECT c.name, COUNT(f.id) as count FROM categories c LEFT JOIN foods f ON c.id = f.category_id GROUP BY c.id")->fetchAll();
$labels = [];
$data = [];
foreach ($cats as $c) {
    $labels[] = $c['name'];
    $data[] = $c['count'];
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - NutriCalc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">NutriCalc Admin</a>
            <div class="d-flex">
                <span class="navbar-text me-3">Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="../pages/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar min-vh-100 p-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="foods.php">Foods</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                </ul>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2>Dashboard</h2>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Users</h5>
                                <h2><?php echo $total_users; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Total Foods</h5>
                                <h2><?php echo $total_foods; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5>Meal Plans Saved</h5>
                                <h2><?php echo $total_plans; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Distribusi Makanan per Kategori</div>
                            <div class="card-body">
                                <canvas id="catChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('catChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: '# of Foods',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: '#198754'
                }]
            }
        });
    </script>
</body>

</html>