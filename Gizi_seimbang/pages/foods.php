<?php
require_once '../inc/functions.php';
include '../inc/header.php';

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Filters
$category_slug = isset($_GET['category']) ? $_GET['category'] : '';
$min_cal = isset($_GET['min_kalori']) ? (int) $_GET['min_kalori'] : 0;
$max_cal = isset($_GET['max_kalori']) ? (int) $_GET['max_kalori'] : 2000;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build Query
$where = ["1=1"];
$params = [];

if ($category_slug) {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->execute([$category_slug]);
    $cat = $stmt->fetch();
    if ($cat) {
        $where[] = "category_id = ?";
        $params[] = $cat['id'];
    }
}

if ($min_cal > 0) {
    $where[] = "calories_kcal >= ?";
    $params[] = $min_cal;
}

if ($max_cal < 2000) {
    $where[] = "calories_kcal <= ?";
    $params[] = $max_cal;
}

$order = "created_at DESC";
if ($sort == 'calories_asc')
    $order = "calories_kcal ASC";
if ($sort == 'calories_desc')
    $order = "calories_kcal DESC";

$where_sql = implode(" AND ", $where);

// Count Total
$stmt = $pdo->prepare("SELECT COUNT(*) FROM foods WHERE $where_sql");
$stmt->execute($params);
$total_foods = $stmt->fetchColumn();
$total_pages = ceil($total_foods / $limit);

// Get Data
$sql = "SELECT * FROM foods WHERE $where_sql ORDER BY $order LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$foods = $stmt->fetchAll();

// Get Categories for Sidebar
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="row">
    <!-- Sidebar Filter -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">Filter</div>
            <div class="card-body">
                <form method="GET" action="foods.php">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo $c['slug']; ?>" <?php echo $category_slug == $c['slug'] ? 'selected' : ''; ?>>
                                    <?php echo $c['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Range Kalori</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Min</span>
                            <input type="number" name="min_kalori" class="form-control" value="<?php echo $min_cal; ?>">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Max</span>
                            <input type="number" name="max_kalori" class="form-control" value="<?php echo $max_cal; ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Terbaru</option>
                            <option value="calories_asc" <?php echo $sort == 'calories_asc' ? 'selected' : ''; ?>>Kalori
                                Terendah</option>
                            <option value="calories_desc" <?php echo $sort == 'calories_desc' ? 'selected' : ''; ?>>Kalori
                                Tertinggi</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Terapkan Filter</button>
                    <a href="foods.php" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Food Grid -->
    <div class="col-md-9">
        <h2 class="mb-4">Katalog Makanan</h2>

        <?php if (count($foods) == 0): ?>
            <div class="alert alert-warning">Tidak ada makanan yang ditemukan dengan filter ini.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($foods as $food): ?>
                    <div class="col">
                        <div class="card h-100 card-food">
                            <img src="<?php echo BASE_URL; ?>assets/img/<?php echo $food['image'] ? $food['image'] : 'default.jpg'; ?>"
                                class="card-img-top" alt="<?php echo esc($food['name']); ?>"
                                style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo esc($food['name']); ?></h5>
                                <div class="mb-2">
                                    <span class="badge bg-info text-dark"><?php echo $food['calories_kcal']; ?> kkal</span>
                                    <span class="badge bg-secondary"><?php echo $food['portion_gram']; ?>g</span>
                                </div>
                                <p class="card-text small text-muted">
                                    P: <?php echo $food['protein_g']; ?>g |
                                    K: <?php echo $food['carbs_g']; ?>g |
                                    L: <?php echo $food['fat_g']; ?>g
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="food.php?id=<?php echo $food['id']; ?>"
                                        class="btn btn-outline-primary btn-sm">Detail</a>
                                    <form method="POST" action="mealplanner.php">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                                        <input type="hidden" name="gram" value="<?php echo $food['portion_gram']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="bi bi-plus-circle"></i> Meal Plan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?php echo $i; ?>&category=<?php echo $category_slug; ?>&min_kalori=<?php echo $min_cal; ?>&max_kalori=<?php echo $max_cal; ?>&sort=<?php echo $sort; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../inc/footer.php'; ?>