<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriCalc - Gizi Seimbang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .card-food {
            transition: transform .2s;
            border: none;
            shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .card-food:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
            padding: 4rem 0;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="<?php echo BASE_URL; ?>">
                <i class="bi bi-heart-pulse-fill"></i> NutriCalc
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/education.php">Edukasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/foods.php">Katalog
                            Makanan</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="<?php echo BASE_URL; ?>pages/calculations.php">Kalkulator Gizi</a></li>
                </ul>
                <form class="d-flex me-3" action="<?php echo BASE_URL; ?>pages/search.php" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Cari makanan..."
                        aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Cari</button>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?php echo BASE_URL; ?>pages/mealplanner.php">
                            <i class="bi bi-basket"></i> Meal Plan
                            <?php if (isset($_SESSION['mealplanner']) && count($_SESSION['mealplanner']) > 0): ?>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo count($_SESSION['mealplanner']); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <?php echo esc($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/profile.php">Profil Saya</a>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/reports.php">Laporan</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger"
                                        href="<?php echo BASE_URL; ?>pages/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/login.php">Login</a>
                        </li>
                        <li class="nav-item"><a class="btn btn-success ms-2"
                                href="<?php echo BASE_URL; ?>pages/register.php">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5" style="min-height: 60vh;">
        <?php
        if (function_exists('flash')) {
            echo flash('success');
            echo flash('error');
        }
        ?>