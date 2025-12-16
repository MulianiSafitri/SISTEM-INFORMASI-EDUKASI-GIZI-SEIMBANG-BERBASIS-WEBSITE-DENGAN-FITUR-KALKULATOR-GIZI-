<?php
require_once '../inc/functions.php';
require_once '../inc/auth.php';

$user = get_current_user_data();
$bmr = 0;
$tdee = 0;
$macros = [];
$is_calculated = false;

// Handle Form Submission (Public or Logged In)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['calculate'])) {
    $weight = (float) $_POST['weight'];
    $height = (float) $_POST['height'];
    $age = (int) $_POST['age'];
    $gender = $_POST['gender'];
    $activity = $_POST['activity'];

    $bmr = calculate_bmr($weight, $height, $age, $gender);
    $tdee = calculate_tdee($bmr, $activity);
    $macros = calculate_macro($tdee);
    $is_calculated = true;
} elseif ($user && $user['weight_kg'] && $user['height_cm']) {
    // Default to user profile if logged in and no form submitted
    $age = date_diff(date_create($user['birthdate']), date_create('today'))->y;
    $bmr = calculate_bmr($user['weight_kg'], $user['height_cm'], $age, $user['gender']);
    $tdee = calculate_tdee($bmr, $user['activity_level']);
    $macros = calculate_macro($tdee);
    $is_calculated = true;
}

include '../inc/header.php';
?>

<div class="container">
    <!-- Calculator Form Section (Matches Image) -->
    <div class="card shadow-sm mb-5 border-0" style="border-radius: 15px;">
        <div class="card-body p-5">
            <h2 class="text-center text-success mb-4"><i class="bi bi-calculator"></i> Kalkulator Gizi Seimbang</h2>

            <h5 class="text-success mb-3"><i class="bi bi-person-fill"></i> Data Diri</h5>

            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Umur (tahun)</label>
                        <input type="number" name="age" class="form-control"
                            value="<?php echo isset($_POST['age']) ? $_POST['age'] : ($user ? date_diff(date_create($user['birthdate']), date_create('today'))->y : ''); ?>"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select">
                            <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') || ($user && $user['gender'] == 'male') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') || ($user && $user['gender'] == 'female') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Aktivitas Harian</label>
                        <select name="activity" class="form-select">
                            <option value="sedentary" <?php echo (isset($_POST['activity']) && $_POST['activity'] == 'sedentary') || ($user && $user['activity_level'] == 'sedentary') ? 'selected' : ''; ?>>Ringan (Jarang Olahraga)</option>
                            <option value="light" <?php echo (isset($_POST['activity']) && $_POST['activity'] == 'light') || ($user && $user['activity_level'] == 'light') ? 'selected' : ''; ?>>Sedang
                                (1-3x/minggu)</option>
                            <option value="moderate" <?php echo (isset($_POST['activity']) && $_POST['activity'] == 'moderate') || ($user && $user['activity_level'] == 'moderate') ? 'selected' : ''; ?>>Menengah (3-5x/minggu)</option>
                            <option value="active" <?php echo (isset($_POST['activity']) && $_POST['activity'] == 'active') || ($user && $user['activity_level'] == 'active') ? 'selected' : ''; ?>>Aktif (6-7x/minggu)</option>
                            <option value="very_active" <?php echo (isset($_POST['activity']) && $_POST['activity'] == 'very_active') || ($user && $user['activity_level'] == 'very_active') ? 'selected' : ''; ?>>Sangat Aktif (Fisik Berat)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="height" class="form-control"
                            value="<?php echo isset($_POST['height']) ? $_POST['height'] : ($user ? $user['height_cm'] : ''); ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="weight" class="form-control"
                            value="<?php echo isset($_POST['weight']) ? $_POST['weight'] : ($user ? $user['weight_kg'] : ''); ?>"
                            required>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" name="calculate" class="btn btn-success btn-lg px-5"><i
                            class="bi bi-check-circle"></i> Hitung Hasil</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($is_calculated): ?>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">Hasil Perhitungan</div>
                    <div class="card-body text-center">
                        <h4 class="card-title text-muted">BMR</h4>
                        <h2 class="text-primary fw-bold"><?php echo number_format($bmr, 0); ?> <small
                                class="fs-6 text-muted">kkal/hari</small></h2>
                        <hr>
                        <h4 class="card-title text-muted">TDEE (Kebutuhan Harian)</h4>
                        <h1 class="display-4 text-success fw-bold"><?php echo number_format($tdee, 0); ?> <small
                                class="fs-6 text-muted">kkal/hari</small></h1>
                        <p class="text-muted mt-2">Ini adalah target kalori Anda untuk mempertahankan berat badan.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white fw-bold">Rekomendasi Makronutrisi</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>Nutrisi</th>
                                        <th>Porsi</th>
                                        <th>Gram</th>
                                        <th>Kalori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-primary">Protein</span></td>
                                        <td>30%</td>
                                        <td><?php echo $macros['protein_g']; ?>g</td>
                                        <td><?php echo $macros['protein_g'] * 4; ?></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">Karbohidrat</span></td>
                                        <td>40%</td>
                                        <td><?php echo $macros['carbs_g']; ?>g</td>
                                        <td><?php echo $macros['carbs_g'] * 4; ?></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">Lemak</span></td>
                                        <td>30%</td>
                                        <td><?php echo $macros['fat_g']; ?>g</td>
                                        <td><?php echo $macros['fat_g'] * 9; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-light border mt-3">
                            <strong>Tips:</strong>
                            <ul class="mb-0 small">
                                <li>Ingin turun berat? Kurangi ~500 kkal (Target:
                                    <strong><?php echo number_format($tdee - 500, 0); ?></strong>)</li>
                                <li>Ingin naik berat? Tambah ~500 kkal (Target:
                                    <strong><?php echo number_format($tdee + 500, 0); ?></strong>)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Monitoring Section (Only if Logged In) -->
        <?php if ($user):
            // Fetch today's meal plans
            $today = date('Y-m-d');
            $stmt = $pdo->prepare("SELECT SUM(total_calories) as cal, SUM(total_protein) as prot, SUM(total_carbs) as carb, SUM(total_fat) as fat FROM mealplans WHERE user_id = ? AND date_created = ?");
            $stmt->execute([$_SESSION['user_id'], $today]);
            $consumed = $stmt->fetch();

            $consumed_cal = $consumed['cal'] ?? 0;
            $consumed_prot = $consumed['prot'] ?? 0;
            $consumed_carb = $consumed['carb'] ?? 0;
            $consumed_fat = $consumed['fat'] ?? 0;

            $perc_cal = $tdee > 0 ? ($consumed_cal / $tdee) * 100 : 0;
            $perc_prot = $macros['protein_g'] > 0 ? ($consumed_prot / $macros['protein_g']) * 100 : 0;
            $perc_carb = $macros['carbs_g'] > 0 ? ($consumed_carb / $macros['carbs_g']) * 100 : 0;
            $perc_fat = $macros['fat_g'] > 0 ? ($consumed_fat / $macros['fat_g']) * 100 : 0;

            $status_msg = "Asupan kalori Anda sudah ideal!";
            $status_color = "success";
            if ($consumed_cal < $tdee - 500) {
                $status_msg = "Asupan kalori masih kurang. Tambahkan camilan sehat!";
                $status_color = "warning";
            } elseif ($consumed_cal > $tdee + 200) {
                $status_msg = "Asupan kalori melebihi target maintenance.";
                $status_color = "danger";
            }
            ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Monitoring Harian
                                (<?php echo date('d M Y'); ?>)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                    <h6 class="text-muted">Total Konsumsi Hari Ini</h6>
                                    <h2 class="display-5 fw-bold text-<?php echo $status_color; ?>">
                                        <?php echo number_format($consumed_cal, 0); ?></h2>
                                    <p class="text-muted">dari target <?php echo number_format($tdee, 0); ?> kkal</p>
                                    <div class="alert alert-<?php echo $status_color; ?> py-2 small mb-0">
                                        <?php echo $status_msg; ?>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="mb-3">Progress Nutrisi</h6>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span>Kalori</span>
                                            <span><?php echo number_format($perc_cal, 0); ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?php echo $status_color; ?>" role="progressbar"
                                                style="width: <?php echo min($perc_cal, 100); ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span>Protein</span>
                                                <span><?php echo number_format($consumed_prot, 0); ?>/<?php echo $macros['protein_g']; ?>g</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: <?php echo min($perc_prot, 100); ?>%"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span>Karbo</span>
                                                <span><?php echo number_format($consumed_carb, 0); ?>/<?php echo $macros['carbs_g']; ?>g</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-warning text-dark" role="progressbar"
                                                    style="width: <?php echo min($perc_carb, 100); ?>%"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span>Lemak</span>
                                                <span><?php echo number_format($consumed_fat, 0); ?>/<?php echo $macros['fat_g']; ?>g</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    style="width: <?php echo min($perc_fat, 100); ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<?php include '../inc/footer.php'; ?>