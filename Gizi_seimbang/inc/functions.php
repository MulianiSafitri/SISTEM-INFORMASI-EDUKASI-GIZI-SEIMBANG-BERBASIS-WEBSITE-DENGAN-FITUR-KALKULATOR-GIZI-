<?php
// inc/functions.php

require_once 'config.php';

// --- Helper Functions ---

function esc($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit;
}

function flash($name, $message = '', $type = 'success')
{
    if ($message) {
        $_SESSION[$name] = [
            'message' => $message,
            'type' => $type
        ];
    } else {
        if (isset($_SESSION[$name])) {
            $flash = $_SESSION[$name];
            unset($_SESSION[$name]);
            return '<div class="alert alert-' . $flash['type'] . ' alert-dismissible fade show" role="alert">
                        ' . $flash['message'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }
    }
    return '';
}

function format_number($number, $decimals = 0)
{
    return number_format($number, $decimals, ',', '.');
}

// --- Nutrition Calculations ---

/**
 * Calculate BMR using Mifflin-St Jeor Equation
 */
function calculate_bmr($weight_kg, $height_cm, $age, $gender)
{
    if ($gender == 'male') {
        return (10 * $weight_kg) + (6.25 * $height_cm) - (5 * $age) + 5;
    } else {
        return (10 * $weight_kg) + (6.25 * $height_cm) - (5 * $age) - 161;
    }
}

/**
 * Calculate TDEE based on Activity Level
 */
function calculate_tdee($bmr, $activity_level)
{
    $multipliers = [
        'sedentary' => 1.2,      // Little or no exercise
        'light' => 1.375,        // Light exercise 1-3 days/week
        'moderate' => 1.55,      // Moderate exercise 3-5 days/week
        'active' => 1.725,       // Hard exercise 6-7 days/week
        'very_active' => 1.9     // Very hard exercise & physical job
    ];
    return $bmr * ($multipliers[$activity_level] ?? 1.2);
}

/**
 * Calculate Macros (Protein, Carbs, Fat) in grams
 * Default ratio: 30% Protein, 40% Carbs, 30% Fat
 * 1g Protein = 4 kcal, 1g Carb = 4 kcal, 1g Fat = 9 kcal
 */
function calculate_macro($tdee, $ratio_protein = 0.3, $ratio_carbs = 0.4, $ratio_fat = 0.3)
{
    $protein_cal = $tdee * $ratio_protein;
    $carbs_cal = $tdee * $ratio_carbs;
    $fat_cal = $tdee * $ratio_fat;

    return [
        'protein_g' => round($protein_cal / 4),
        'carbs_g' => round($carbs_cal / 4),
        'fat_g' => round($fat_cal / 9)
    ];
}

// --- Meal Planner (Session Based) ---

function get_meal_planner()
{
    return $_SESSION['mealplanner'] ?? [];
}

function add_to_meal_planner($food_id, $gram)
{
    global $pdo;

    // Check if food exists
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE id = ?");
    $stmt->execute([$food_id]);
    $food = $stmt->fetch();

    if (!$food)
        return false;

    // Calculate nutrition for specific gram
    $ratio = $gram / $food['portion_gram'];

    $item = [
        'id' => $food['id'],
        'name' => $food['name'],
        'gram' => $gram,
        'calories' => $food['calories_kcal'] * $ratio,
        'protein' => $food['protein_g'] * $ratio,
        'carbs' => $food['carbs_g'] * $ratio,
        'fat' => $food['fat_g'] * $ratio,
        'image' => $food['image']
    ];

    $_SESSION['mealplanner'][$food_id] = $item;
    return true;
}

function remove_from_meal_planner($food_id)
{
    if (isset($_SESSION['mealplanner'][$food_id])) {
        unset($_SESSION['mealplanner'][$food_id]);
    }
}

function get_meal_planner_totals()
{
    $totals = [
        'calories' => 0,
        'protein' => 0,
        'carbs' => 0,
        'fat' => 0
    ];

    $planner = get_meal_planner();
    foreach ($planner as $item) {
        $totals['calories'] += $item['calories'];
        $totals['protein'] += $item['protein'];
        $totals['carbs'] += $item['carbs'];
        $totals['fat'] += $item['fat'];
    }
    return $totals;
}

// --- Search & Recommendations (TF-IDF Simplified) ---

/**
 * Simple Tokenizer & Stopword Removal (Bahasa Indonesia)
 * Note: A full Sastrawi implementation requires Composer. 
 * This is a lightweight procedural implementation for XAMPP compatibility without Composer.
 */
function preprocess_text($text)
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s]/', '', $text); // Remove punctuation

    // Basic Indonesian Stopwords
    $stopwords = ['dan', 'atau', 'yang', 'untuk', 'pada', 'ke', 'di', 'dari', 'ini', 'itu', 'dengan', 'adalah', 'sebagai', 'karena', 'jika', 'bisa', 'ada', 'saya', 'kita', 'mereka', 'anda', 'akan', 'sudah', 'telah', 'sedang', 'masih', 'juga', 'lain', 'sangat', 'tentang', 'seperti', 'oleh', 'saat', 'harus', 'dapat', 'kami', 'ia', 'dia', 'mereka', 'apa', 'siapa', 'kapan', 'dimana', 'mengapa', 'bagaimana'];

    $tokens = explode(' ', $text);
    $filtered = array_filter($tokens, function ($token) use ($stopwords) {
        return !in_array($token, $stopwords) && strlen($token) > 2;
    });

    // Stemming (Very Basic - Sastrawi is complex, this is a placeholder for "Stemming")
    // In a real scenario without Composer, we'd skip complex stemming or include a single-file stemmer.
    // We will just use the tokens as is for this demo to ensure it runs.

    return array_values($filtered);
}

function calculate_tfidf_vector($tokens, $all_documents_tokens)
{
    // 1. Calculate TF
    $tf = array_count_values($tokens);
    $total_tokens = count($tokens);
    foreach ($tf as $term => $count) {
        $tf[$term] = $count / $total_tokens;
    }

    // 2. Calculate IDF
    $idf = [];
    $total_docs = count($all_documents_tokens);

    // Get all unique terms from this document
    $unique_terms = array_keys($tf);

    foreach ($unique_terms as $term) {
        $docs_with_term = 0;
        foreach ($all_documents_tokens as $doc_tokens) {
            if (in_array($term, $doc_tokens)) {
                $docs_with_term++;
            }
        }
        $idf[$term] = log($total_docs / ($docs_with_term ?: 1));
    }

    // 3. Calculate TF-IDF
    $tfidf = [];
    foreach ($tf as $term => $val) {
        $tfidf[$term] = $val * $idf[$term];
    }

    return $tfidf;
}

function cosine_similarity($vec1, $vec2)
{
    $dot_product = 0;
    $mag1 = 0;
    $mag2 = 0;

    $all_terms = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));

    foreach ($all_terms as $term) {
        $v1 = $vec1[$term] ?? 0;
        $v2 = $vec2[$term] ?? 0;

        $dot_product += $v1 * $v2;
        $mag1 += $v1 * $v1;
        $mag2 += $v2 * $v2;
    }

    $mag1 = sqrt($mag1);
    $mag2 = sqrt($mag2);

    if ($mag1 * $mag2 == 0)
        return 0;
    return $dot_product / ($mag1 * $mag2);
}

function get_recommendations($food_id, $limit = 5)
{
    global $pdo;

    // Get target food vector
    $stmt = $pdo->prepare("SELECT tfidf_vector FROM tfidf_cache WHERE food_id = ?");
    $stmt->execute([$food_id]);
    $target = $stmt->fetch();

    if (!$target || !$target['tfidf_vector']) {
        // If not cached, we might need to build cache (omitted for speed, assume cache exists or fallback)
        // Fallback: return random foods from same category
        $stmt = $pdo->prepare("SELECT category_id FROM foods WHERE id = ?");
        $stmt->execute([$food_id]);
        $cat = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT * FROM foods WHERE category_id = ? AND id != ? LIMIT ?");
        $stmt->execute([$cat['category_id'], $food_id, $limit]);
        return $stmt->fetchAll();
    }

    $target_vec = json_decode($target['tfidf_vector'], true);

    // Get all other vectors
    $stmt = $pdo->query("SELECT food_id, tfidf_vector FROM tfidf_cache WHERE food_id != $food_id");
    $others = $stmt->fetchAll();

    $scores = [];
    foreach ($others as $other) {
        $other_vec = json_decode($other['tfidf_vector'], true);
        if ($other_vec) {
            $score = cosine_similarity($target_vec, $other_vec);
            $scores[$other['food_id']] = $score;
        }
    }

    arsort($scores);
    $top_ids = array_slice(array_keys($scores), 0, $limit);

    if (empty($top_ids))
        return [];

    $placeholders = str_repeat('?,', count($top_ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE id IN ($placeholders)");
    $stmt->execute($top_ids);
    $foods = $stmt->fetchAll();

    // Reorder to match score order
    $ordered_foods = [];
    foreach ($top_ids as $id) {
        foreach ($foods as $f) {
            if ($f['id'] == $id) {
                $f['similarity_score'] = $scores[$id];
                $ordered_foods[] = $f;
                break;
            }
        }
    }

    return $ordered_foods;
}

// --- Search Function ---
function search_foods($query, $limit = 20, $offset = 0)
{
    global $pdo;

    // Basic SQL Search first
    $search_term = "%$query%";
    $stmt = $pdo->prepare("
        SELECT * FROM foods 
        WHERE name LIKE ? OR description LIKE ? OR ingredients LIKE ?
        LIMIT ? OFFSET ?
    ");
    // PDO LIMIT needs integer binding
    $stmt->bindValue(1, $search_term);
    $stmt->bindValue(2, $search_term);
    $stmt->bindValue(3, $search_term);
    $stmt->bindValue(4, (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(5, (int) $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

?>