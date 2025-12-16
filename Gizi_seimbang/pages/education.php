<?php
require_once '../inc/functions.php';
include '../inc/header.php';
?>

<div class="container">
    <div class="text-center mb-5 pt-4">
        <span class="badge bg-success bg-opacity-10 text-success mb-2 px-3 py-2 rounded-pill">Pusat Pengetahuan</span>
        <h1 class="display-4 fw-bold text-dark mb-3">Edukasi Gizi Seimbang</h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">Panduan lengkap untuk pola hidup sehat, nutrisi yang tepat, dan pemahaman mendalam tentang kebutuhan tubuh Anda.</p>
    </div>

    <!-- Section 1: Apa itu Gizi Seimbang? -->
    <div class="card border-0 shadow-sm mb-5 overflow-hidden">
        <div class="row g-0 align-items-center">
            <div class="col-md-6">
                <img src="https://upk.kemkes.go.id/new/image/content/2020/09/tumpeng-gizi-seimbang.jpg" class="img-fluid w-100 h-100 object-fit-cover" alt="Tumpeng Gizi Seimbang" style="min-height: 400px;" onerror="this.src='https://via.placeholder.com/600x400?text=Tumpeng+Gizi+Seimbang'">
            </div>
            <div class="col-md-6">
                <div class="card-body p-5">
                    <h2 class="text-success fw-bold mb-4"><i class="bi bi-book-half me-2"></i> Apa itu Gizi Seimbang?</h2>
                    <p class="card-text text-secondary mb-4"><strong>Gizi Seimbang</strong> adalah susunan pangan sehari-hari yang mengandung zat gizi dalam jenis dan jumlah yang sesuai dengan kebutuhan tubuh. Prinsip ini memperhatikan keanekaragaman pangan, aktivitas fisik, perilaku hidup bersih, dan pemantauan berat badan.</p>
                    
                    <div class="bg-success bg-opacity-10 p-4 rounded-3">
                        <h5 class="text-success fw-bold mb-3"><i class="bi bi-check-circle-fill me-2"></i> 4 Pilar Gizi Seimbang</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="bi bi-dot text-success"></i> Mengonsumsi aneka ragam pangan</li>
                            <li class="mb-2"><i class="bi bi-dot text-success"></i> Membiasakan perilaku hidup bersih</li>
                            <li class="mb-2"><i class="bi bi-dot text-success"></i> Melakukan aktivitas fisik teratur</li>
                            <li><i class="bi bi-dot text-success"></i> Memantau Berat Badan (BB) ideal</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Isi Piringku -->
    <div class="row align-items-center mb-5 py-5">
        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <div class="position-relative">
                <div class="position-absolute top-0 start-0 translate-middle bg-warning rounded-circle" style="width: 100px; height: 100px; opacity: 0.2; z-index: -1;"></div>
                <img src="https://promkes.kemkes.go.id/image/content/2020/09/isi-piringku.jpg" class="img-fluid rounded-4 shadow-lg position-relative" alt="Isi Piringku" onerror="this.src='https://via.placeholder.com/600x400?text=Isi+Piringku'">
                <div class="position-absolute bottom-0 end-0 translate-middle bg-primary rounded-circle" style="width: 150px; height: 150px; opacity: 0.1; z-index: -1;"></div>
            </div>
        </div>
        <div class="col-lg-6 order-lg-1">
            <h2 class="text-primary fw-bold mb-3"><i class="bi bi-pie-chart-fill me-2"></i> Konsep "Isi Piringku"</h2>
            <p class="lead text-muted mb-4">Kampanye dari Kementerian Kesehatan untuk menggambarkan porsi makan ideal dalam satu piring.</p>
            
            <div class="row g-4">
                <div class="col-sm-6">
                    <div class="card h-100 border-0 shadow-sm bg-warning bg-opacity-10">
                        <div class="card-body text-center p-4">
                            <div class="bg-white rounded-circle d-inline-flex p-3 mb-3 shadow-sm text-warning">
                                <i class="bi bi-flower1 fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark">50% Buah & Sayur</h5>
                            <p class="small text-muted mb-0">Sumber serat, vitamin, dan mineral penting untuk imunitas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card h-100 border-0 shadow-sm bg-primary bg-opacity-10">
                        <div class="card-body text-center p-4">
                            <div class="bg-white rounded-circle d-inline-flex p-3 mb-3 shadow-sm text-primary">
                                <i class="bi bi-egg-fried fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark">50% Karbo & Protein</h5>
                            <p class="small text-muted mb-0">Sumber energi dan zat pembangun untuk aktivitas harian.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Pentingnya Makronutrisi -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">Mengenal Makronutrisi</h2>
        <p class="text-muted">Tiga komponen utama yang dibutuhkan tubuh dalam jumlah besar</p>
    </div>
    
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <div class="col">
            <div class="card h-100 border-0 shadow hover-card text-center overflow-hidden">
                <div class="card-header bg-warning text-white border-0 py-3">
                    <i class="bi bi-lightning-fill display-4"></i>
                </div>
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold mb-3">Karbohidrat</h4>
                    <p class="card-text text-muted">Sumber energi utama. Pilih karbohidrat kompleks (nasi merah, gandum, ubi) yang kaya serat dan memberi rasa kenyang lebih lama.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow hover-card text-center overflow-hidden">
                <div class="card-header bg-primary text-white border-0 py-3">
                    <i class="bi bi-bricks display-4"></i>
                </div>
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold mb-3">Protein</h4>
                    <p class="card-text text-muted">Zat pembangun sel. Penting untuk pertumbuhan otot dan perbaikan jaringan. Sumber: daging, ikan, telur, tempe, tahu.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow hover-card text-center overflow-hidden">
                <div class="card-header bg-danger text-white border-0 py-3">
                    <i class="bi bi-droplet-fill display-4"></i>
                </div>
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold mb-3">Lemak</h4>
                    <p class="card-text text-muted">Cadangan energi & pelarut vitamin. Pilih lemak tak jenuh (alpukat, zaitun, ikan). Batasi lemak jenuh dan trans.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="card border-0 bg-success text-white rounded-4 overflow-hidden mb-5 shadow-lg">
        <div class="card-body p-5 text-center position-relative">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
            <h3 class="display-5 fw-bold mb-3 position-relative">Sudah Siap Hidup Sehat?</h3>
            <p class="lead mb-4 position-relative">Mulai hitung kebutuhan kalori harianmu dan rencanakan menu makan sehat sekarang.</p>
            <div class="d-flex justify-content-center gap-3 position-relative">
                <a href="calculations.php" class="btn btn-light btn-lg fw-bold text-success shadow-sm">Hitung Kalori Saya</a>
                <a href="consultation.php" class="btn btn-outline-light btn-lg fw-bold">Konsultasi Ahli</a>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card:hover {
        transform: translateY(-10px);
        transition: 0.3s;
    }
</style>

<?php include '../inc/footer.php'; ?>