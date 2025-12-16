<?php
require_once '../inc/functions.php';
require_once '../inc/auth.php';

require_login();

$planner = get_meal_planner();
if (empty($planner)) {
    flash('error', 'Keranjang makanan kosong.');
    redirect('pages/mealplanner.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $totals = get_meal_planner_totals();

    try {
        $pdo->beginTransaction();

        // Insert Mealplan
        $stmt = $pdo->prepare("INSERT INTO mealplans (user_id, title, date_created, total_calories, total_protein, total_carbs, total_fat) VALUES (?, ?, NOW(), ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $title,
            $totals['calories'],
            $totals['protein'],
            $totals['carbs'],
            $totals['fat']
        ]);

        $mealplan_id = $pdo->lastInsertId();

        // Insert Items
        $stmt_item = $pdo->prepare("INSERT INTO mealplan_items (mealplan_id, food_id, qty_portion, gram_per_portion) VALUES (?, ?, ?, ?)");

        foreach ($planner as $food_id => $item) {
            // Assuming 1 portion = standard portion in DB, but here we save gram directly
            // We'll store gram as gram_per_portion and qty as 1 for simplicity in this model
            $stmt_item->execute([
                $mealplan_id,
                $food_id,
                1,
                $item['gram']
            ]);
        }

        $pdo->commit();

        // Clear session
        unset($_SESSION['mealplanner']);

        flash('success', 'Rencana makan berhasil disimpan!');
        redirect('pages/profile.php'); // Or redirect to a "My Mealplans" page

    } catch (Exception $e) {
        $pdo->rollBack();
        flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        redirect('pages/mealplanner.php');
    }
}

include '../inc/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Simpan Rencana Makan</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Rencana Makan</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Menu Diet Senin"
                            required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>