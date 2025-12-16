<?php
require_once '../inc/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT f.*, c.name as category_name FROM foods f JOIN categories c ON f.category_id = c.id WHERE f.id = ?");
$stmt->execute([$id]);
$food = $stmt->fetch();

if (!$food) {
    flash('error', 'Makanan tidak ditemukan.');
    redirect('pages/foods.php');
}

// Get Recommendations
$recommendations = get_recommendations($id, 5);

include '../inc/header.php';
?>

<div class="row">
    <div class="col-md-6 mb-4">
        <img src="<?php echo BASE_URL; ?>assets/img/<?php echo $food['image'] ? $food['image'] : 'default.jpg'; ?>"
            class="img-fluid rounded shadow" alt="<?php echo esc($food['name']); ?>">
    </div>
    <div class="col-md-6">
        <span class="badge bg-secondary mb-2"><?php echo esc($food['category_name']); ?></span>
        <h1 class="mb-3"><?php echo esc($food['name']); ?></h1>
        <p class="lead"><?php echo esc($food['description']); ?></p>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">Informasi Nilai Gizi (per
                <?php echo $food['portion_gram']; ?>g)</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-3">
                        <h3 class="text-success"><?php echo $food['calories_kcal']; ?></h3>
                        <small>Kalori (kkal)</small>
                    </div>
                    <div class="col-3">
                        <h3 class="text-primary"><?php echo $food['protein_g']; ?></h3>
                        <small>Protein (g)</small>
                    </div>
                    <div class="col-3">
                        <h3 class="text-warning"><?php echo $food['carbs_g']; ?></h3>
                        <small>Karbo (g)</small>
                    </div>
                    <div class="col-3">
                        <h3 class="text-danger"><?php echo $food['fat_g']; ?></h3>
                        <small>Lemak (g)</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <strong>Serat:</strong> <?php echo $food['fiber_g']; ?>g
                    </div>
                    <div class="col-6">
                        <strong>Sodium:</strong> <?php echo $food['sodium_mg']; ?>mg
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Tambah ke Meal Planner</h5>
                <form method="POST" action="mealplanner.php" class="row g-3">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                    <div class="col-auto">
                        <label for="gram" class="visually-hidden">Gram</label>
                        <input type="number" class="form-control" name="gram"
                            value="<?php echo $food['portion_gram']; ?>" placeholder="Berat (gram)">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success mb-3">Tambah</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($food['ingredients']): ?>
            <div class="mb-4">
                <h5>Komposisi / Bahan:</h5>
                <p><?php echo esc($food['ingredients']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<hr class="my-5">

<h3 class="mb-4">Rekomendasi Makanan Serupa</h3>
<div class="row row-cols-1 row-cols-md-5 g-4">
    <?php foreach ($recommendations as $rec): ?>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <img src="<?php echo BASE_URL; ?>assets/img/<?php echo $rec['image'] ? $rec['image'] : 'default.jpg'; ?>"
                    class="card-img-top" style="height: 120px; object-fit: cover;">
                <div class="card-body p-2">
                    <h6 class="card-title text-truncate"><?php echo esc($rec['name']); ?></h6>
                    <small class="text-muted"><?php echo $rec['calories_kcal']; ?> kkal</small>
                    <a href="food.php?id=<?php echo $rec['id']; ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../inc/footer.php'; ?>