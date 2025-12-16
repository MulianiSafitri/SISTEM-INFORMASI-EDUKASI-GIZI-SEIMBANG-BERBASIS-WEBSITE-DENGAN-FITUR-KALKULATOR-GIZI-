<?php
require_once '../inc/functions.php';
require_once '../inc/auth.php';

if (is_logged_in()) {
    redirect('pages/profile.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = register_user($_POST);
    if ($result['status']) {
        flash('success', $result['message']);
        redirect('pages/login.php');
    } else {
        flash('error', $result['message']);
    }
}

include '../inc/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Daftar Akun Baru</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Daftar</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>