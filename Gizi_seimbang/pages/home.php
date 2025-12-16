<?php
require_once '../inc/functions.php';
include '../inc/header.php';
?>

<!-- Hero Section -->
<div class="p-5 mb-5 bg-dark text-white rounded-3 hero-section"
    style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1490818387583-1baba5e638af?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center;">
    <div class="container-fluid py-5 text-center">
        <h1 class="display-4 fw-bold mb-3">Hidup Sehat dengan Gizi Seimbang</h1>
        <p class="fs-5 mb-4 mx-auto" style="max-width: 700px;">Hitung kebutuhan kalori harian Anda, temukan makanan
            bernutrisi, dan rencanakan pola makan sehat Anda bersama NutriCalc.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="calculations.php" class="btn btn-success btn-lg px-4 gap-3 fw-bold"><i
                    class="bi bi-calculator"></i> Hitung Gizi Sekarang</a>
            <a href="foods.php" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-search"></i> Lihat Katalog
                Makanan</a>
        </div>
    </div>
</div>

<div class="row align-items-md-stretch mb-5">
    <div class="col-md-6 mb-4">
        <div class="h-100 p-5 text-white bg-primary rounded-3 shadow-sm"
            style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-activity display-4 me-3"></i>
                <h2 class="mb-0">Kalkulator BMR & TDEE</h2>
            </div>
            <p>Ketahui berapa banyak kalori yang tubuh Anda butuhkan setiap hari berdasarkan berat, tinggi, umur, dan
                aktivitas fisik. Metode Mifflin-St Jeor yang akurat.</p>
            <a href="calculations.php" class="btn btn-light text-primary fw-bold stretched-link">Coba Kalkulator</a>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="h-100 p-5 bg-light border rounded-3 shadow-sm position-relative overflow-hidden">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-calendar-week display-4 me-3 text-success"></i>
                <h2 class="mb-0 text-success">Meal Planner</h2>
            </div>
            <p>Buat rencana makan harian Anda. Pilih dari database makanan kami yang lengkap, termasuk makanan
                tradisional Indonesia seperti Ayam, Tempe, dan lainnya.</p>
            <a href="mealplanner.php" class="btn btn-outline-success stretched-link">Buat Rencana Makan</a>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12 text-center mb-4">
        <h3 class="fw-bold text-success"><i class="bi bi-stars"></i> Makanan Terbaru</h3>
        <p class="text-muted">Temukan inspirasi makanan sehat untuk menu harian Anda</p>
    </div>
    <div class="col-12">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM foods ORDER BY created_at DESC LIMIT 4");
            while ($food = $stmt->fetch()):
                ?>
                <div class="col">
                    <div class="card h-100 card-food shadow-sm border-0">
                        <div class="position-relative">
                            <img src="<?php echo BASE_URL; ?>assets/img/<?php echo $food['image'] ? $food['image'] : 'default.jpg'; ?>"
                                class="card-img-top" alt="<?php echo esc($food['name']); ?>"
                                style="height: 200px; object-fit: cover;">
                            <span class="position-absolute top-0 end-0 bg-success text-white badge m-2 p-2 rounded-pill">
                                <?php echo $food['calories_kcal']; ?> kkal
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark"><?php echo esc($food['name']); ?></h5>
                            <p class="card-text text-muted small"><?php echo substr(esc($food['description']), 0, 80); ?>...
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 pb-3">
                            <a href="food.php?id=<?php echo $food['id']; ?>"
                                class="btn btn-outline-primary w-100 rounded-pill">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4">
            <a href="foods.php" class="btn btn-link text-decoration-none">Lihat Semua Makanan <i
                    class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>