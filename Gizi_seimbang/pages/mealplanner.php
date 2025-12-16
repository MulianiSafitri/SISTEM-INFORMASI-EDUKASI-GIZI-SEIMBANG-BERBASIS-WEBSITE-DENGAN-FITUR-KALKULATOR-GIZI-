<?php
require_once '../inc/functions.php';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add') {
        $food_id = $_POST['food_id'];
        $gram = (float) $_POST['gram'];
        if (add_to_meal_planner($food_id, $gram)) {
            flash('success', 'Makanan ditambahkan ke rencana makan.');
        } else {
            flash('error', 'Gagal menambahkan makanan.');
        }
    } elseif ($action == 'remove') {
        $food_id = $_POST['food_id'];
        remove_from_meal_planner($food_id);
        flash('success', 'Makanan dihapus.');
    } elseif ($action == 'update') {
        $food_id = $_POST['food_id'];
        $gram = (float) $_POST['gram'];
        add_to_meal_planner($food_id, $gram); // Re-add updates it
        flash('success', 'Porsi diperbarui.');
    }

    // Redirect to avoid form resubmission
    redirect('pages/mealplanner.php');
}

$planner = get_meal_planner();
$totals = get_meal_planner_totals();

include '../inc/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">Meal Planner (Keranjang Makanan)</h2>

        <?php if (empty($planner)): ?>
            <div class="alert alert-info">
                Rencana makan Anda masih kosong. <a href="foods.php">Cari makanan</a> untuk ditambahkan.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Makanan</th>
                            <th>Porsi (gram)</th>
                            <th>Kalori</th>
                            <th>Makro (P/K/L)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($planner as $id => $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo BASE_URL; ?>assets/img/<?php echo $item['image'] ? $item['image'] : 'default.jpg'; ?>"
                                            class="rounded me-2" width="50" height="50" style="object-fit: cover;">
                                        <strong><?php echo esc($item['name']); ?></strong>
                                    </div>
                                </td>
                                <td style="width: 150px;">
                                    <form method="POST" class="d-flex">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="food_id" value="<?php echo $id; ?>">
                                        <input type="number" name="gram" value="<?php echo $item['gram']; ?>"
                                            class="form-control form-control-sm me-1" style="width: 70px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                                class="bi bi-check"></i></button>
                                    </form>
                                </td>
                                <td><?php echo number_format($item['calories'], 1); ?> kkal</td>
                                <td>
                                    <small>
                                        P: <?php echo number_format($item['protein'], 1); ?><br>
                                        K: <?php echo number_format($item['carbs'], 1); ?><br>
                                        L: <?php echo number_format($item['fat'], 1); ?>
                                    </small>
                                </td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="food_id" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total:</strong></td>
                            <td><strong><?php echo number_format($totals['calories'], 1); ?> kkal</strong></td>
                            <td>
                                <small>
                                    P: <?php echo number_format($totals['protein'], 1); ?> |
                                    K: <?php echo number_format($totals['carbs'], 1); ?> |
                                    L: <?php echo number_format($totals['fat'], 1); ?>
                                </small>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a href="foods.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Tambah Makanan
                    Lain</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="meal_save.php" class="btn btn-success btn-lg">Simpan Rencana Makan <i class="bi bi-save"></i></a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-lg">Login untuk Menyimpan</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Ringkasan Nutrisi</h5>
                <p>Bandingkan dengan kebutuhan harian Anda (TDEE).</p>
                <!-- Chart.js could go here -->
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent d-flex justify-content-between">
                        <span>Kalori</span>
                        <strong><?php echo number_format($totals['calories'], 0); ?> kkal</strong>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between">
                        <span>Protein</span>
                        <strong><?php echo number_format($totals['protein'], 1); ?> g</strong>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between">
                        <span>Karbohidrat</span>
                        <strong><?php echo number_format($totals['carbs'], 1); ?> g</strong>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between">
                        <span>Lemak</span>
                        <strong><?php echo number_format($totals['fat'], 1); ?> g</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>