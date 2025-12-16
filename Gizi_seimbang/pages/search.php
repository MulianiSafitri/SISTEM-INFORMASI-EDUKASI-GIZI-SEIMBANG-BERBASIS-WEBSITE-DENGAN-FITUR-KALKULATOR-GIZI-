<?php
require_once '../inc/functions.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';
$results = [];

if ($query) {
    $results = search_foods($query);
}

include '../inc/header.php';
?>

<div class="container">
    <h2 class="mb-4">Hasil Pencarian: "<?php echo esc($query); ?>"</h2>

    <?php if (empty($results)): ?>
        <div class="alert alert-info">Tidak ada makanan yang ditemukan. Coba kata kunci lain.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($results as $food): ?>
                <a href="food.php?id=<?php echo $food['id']; ?>"
                    class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                    <img src="<?php echo BASE_URL; ?>assets/img/<?php echo $food['image'] ? $food['image'] : 'default.jpg'; ?>"
                        alt="twbs" width="64" height="64" class="rounded-circle flex-shrink-0" style="object-fit: cover;">
                    <div class="d-flex gap-2 w-100 justify-content-between">
                        <div>
                            <h6 class="mb-0"><?php echo esc($food['name']); ?></h6>
                            <p class="mb-0 opacity-75"><?php echo substr(esc($food['description']), 0, 100); ?>...</p>
                            <small class="text-muted">
                                <?php echo $food['calories_kcal']; ?> kkal |
                                P: <?php echo $food['protein_g']; ?>g |
                                K: <?php echo $food['carbs_g']; ?>g |
                                L: <?php echo $food['fat_g']; ?>g
                            </small>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../inc/footer.php'; ?>