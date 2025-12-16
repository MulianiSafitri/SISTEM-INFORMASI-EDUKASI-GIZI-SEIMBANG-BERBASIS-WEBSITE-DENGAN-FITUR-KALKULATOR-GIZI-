<?php
// admin/food_edit.php
require_once '../inc/config.php';
require_once '../inc/functions.php';
require_once '../inc/admin_auth.php';
require_admin_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM foods WHERE id = ?");
$stmt->execute([$id]);
$food = $stmt->fetch();

if (!$food) {
    die("Food not found");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $portion = $_POST['portion_gram'];
    $cal = $_POST['calories_kcal'];
    $prot = $_POST['protein_g'];
    $carbs = $_POST['carbs_g'];
    $fat = $_POST['fat_g'];
    $fiber = $_POST['fiber_g'];
    $sodium = $_POST['sodium_mg'];
    $ingredients = $_POST['ingredients'];

    $image = $food['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/' . $image);
    }

    $stmt = $pdo->prepare("UPDATE foods SET category_id=?, name=?, description=?, portion_gram=?, calories_kcal=?, protein_g=?, carbs_g=?, fat_g=?, fiber_g=?, sodium_mg=?, ingredients=?, image=? WHERE id=?");
    $stmt->execute([$category_id, $name, $description, $portion, $cal, $prot, $carbs, $fat, $fiber, $sodium, $ingredients, $image, $id]);

    header("Location: foods.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Food - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4 mb-5">
        <h2>Edit Food</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo esc($food['name']); ?>"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Category</label>
                    <select name="category_id" class="form-select">
                        <?php foreach ($categories as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $food['category_id'] ? 'selected' : ''; ?>><?php echo $c['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"
                    rows="3"><?php echo esc($food['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label>Ingredients</label>
                <textarea name="ingredients" class="form-control"
                    rows="2"><?php echo esc($food['ingredients']); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-2 mb-3"><label>Portion (g)</label><input type="number" step="0.1" name="portion_gram"
                        class="form-control" value="<?php echo $food['portion_gram']; ?>"></div>
                <div class="col-md-2 mb-3"><label>Calories</label><input type="number" step="0.1" name="calories_kcal"
                        class="form-control" value="<?php echo $food['calories_kcal']; ?>" required></div>
                <div class="col-md-2 mb-3"><label>Protein (g)</label><input type="number" step="0.1" name="protein_g"
                        class="form-control" value="<?php echo $food['protein_g']; ?>"></div>
                <div class="col-md-2 mb-3"><label>Carbs (g)</label><input type="number" step="0.1" name="carbs_g"
                        class="form-control" value="<?php echo $food['carbs_g']; ?>"></div>
                <div class="col-md-2 mb-3"><label>Fat (g)</label><input type="number" step="0.1" name="fat_g"
                        class="form-control" value="<?php echo $food['fat_g']; ?>"></div>
                <div class="col-md-1 mb-3"><label>Fiber</label><input type="number" step="0.1" name="fiber_g"
                        class="form-control" value="<?php echo $food['fiber_g']; ?>"></div>
                <div class="col-md-1 mb-3"><label>Sodium</label><input type="number" step="0.1" name="sodium_mg"
                        class="form-control" value="<?php echo $food['sodium_mg']; ?>"></div>
            </div>
            <div class="mb-3">
                <label>Current Image</label><br>
                <?php if ($food['image']): ?>
                    <img src="../assets/img/<?php echo $food['image']; ?>" width="100" class="mb-2">
                <?php endif; ?>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Food</button>
            <a href="foods.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>