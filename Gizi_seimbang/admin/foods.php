<?php
// admin/foods.php
require_once '../inc/config.php';
require_once '../inc/functions.php';
require_once '../inc/admin_auth.php';
require_admin_login();

// Delete Logic
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM foods WHERE id = ?")->execute([$id]);
    header("Location: foods.php");
    exit;
}

$foods = $pdo->query("SELECT f.*, c.name as category_name FROM foods f JOIN categories c ON f.category_id = c.id ORDER BY f.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Manage Foods - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Manage Foods</h2>
            <div>
                <a href="index.php" class="btn btn-secondary">Back</a>
                <a href="food_add.php" class="btn btn-primary">Add New Food</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Calories</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($foods as $f): ?>
                    <tr>
                        <td><?php echo $f['id']; ?></td>
                        <td><img src="../assets/img/<?php echo $f['image'] ? $f['image'] : 'default.jpg'; ?>" width="50">
                        </td>
                        <td><?php echo esc($f['name']); ?></td>
                        <td><?php echo esc($f['category_name']); ?></td>
                        <td><?php echo $f['calories_kcal']; ?></td>
                        <td>
                            <a href="food_edit.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="?delete=<?php echo $f['id']; ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>