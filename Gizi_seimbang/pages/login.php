<?php
require_once '../inc/functions.php';
require_once '../inc/auth.php';

if (is_logged_in()) {
    redirect('pages/profile.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login_user($email, $password)) {
        flash('success', 'Login berhasil! Selamat datang kembali.');
        redirect('pages/profile.php');
    } else {
        flash('error', 'Email atau password salah.');
    }
}

include '../inc/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Login NutriCalc</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Masuk</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
                    <p><a href="#" class="text-muted small">Lupa password?</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>