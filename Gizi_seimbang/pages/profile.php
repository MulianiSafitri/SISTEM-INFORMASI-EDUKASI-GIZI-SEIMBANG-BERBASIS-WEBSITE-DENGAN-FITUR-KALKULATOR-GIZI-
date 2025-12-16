<?php
require_once '../inc/functions.php';
require_once '../inc/auth.php';

require_login();

$user = get_current_user_data();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $height = $_POST['height_cm'];
    $weight = $_POST['weight_kg'];
    $activity = $_POST['activity_level'];
    $phone = $_POST['phone'];

    $stmt = $pdo->prepare("UPDATE users SET gender=?, birthdate=?, height_cm=?, weight_kg=?, activity_level=?, phone=? WHERE id=?");
    if ($stmt->execute([$gender, $birthdate, $height, $weight, $activity, $phone, $_SESSION['user_id']])) {
        flash('success', 'Profil berhasil diperbarui.');
        $user = get_current_user_data(); // Refresh data
    } else {
        flash('error', 'Gagal memperbarui profil.');
    }
}

include '../inc/header.php';
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3"
                    style="width: 100px; height: 100px;">
                    <i class="bi bi-person-fill fs-1 text-secondary"></i>
                </div>
                <h4><?php echo esc($user['name']); ?></h4>
                <p class="text-muted"><?php echo esc($user['email']); ?></p>
                <hr>
                <div class="text-start">
                    <p><strong>Member sejak:</strong> <?php echo date('d M Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Data Fisik & Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Data ini digunakan untuk menghitung kebutuhan kalori harian Anda
                    (BMR & TDEE).
                </div>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Laki-laki
                                </option>
                                <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>
                                    Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birthdate" class="form-control"
                                value="<?php echo $user['birthdate']; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tinggi Badan (cm)</label>
                            <input type="number" step="0.1" name="height_cm" class="form-control"
                                value="<?php echo $user['height_cm']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" step="0.1" name="weight_kg" class="form-control"
                                value="<?php echo $user['weight_kg']; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tingkat Aktivitas</label>
                        <select name="activity_level" class="form-select">
                            <option value="sedentary" <?php echo $user['activity_level'] == 'sedentary' ? 'selected' : ''; ?>>Sedentary (Jarang olahraga)</option>
                            <option value="light" <?php echo $user['activity_level'] == 'light' ? 'selected' : ''; ?>>
                                Light (Olahraga 1-3x/minggu)</option>
                            <option value="moderate" <?php echo $user['activity_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate (Olahraga 3-5x/minggu)</option>
                            <option value="active" <?php echo $user['activity_level'] == 'active' ? 'selected' : ''; ?>>
                                Active (Olahraga 6-7x/minggu)</option>
                            <option value="very_active" <?php echo $user['activity_level'] == 'very_active' ? 'selected' : ''; ?>>Very Active (Fisik berat/atlet)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo esc($user['phone']); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>