<?php
// Fungsi normalisasi
function normalize($matrix, $num_alternatives, $num_criteria) {
    $normalized = array();
    for ($j = 0; $j < $num_criteria; $j++) {
        $sum = 0;
        for ($i = 0; $i < $num_alternatives; $i++) {
            $sum += $matrix[$i][$j];
        }
        for ($i = 0; $i < $num_alternatives; $i++) {
            $normalized[$i][$j] = $matrix[$i][$j] / $sum;
        }
    }
    return $normalized;
}

// Fungsi pembobotan
function weighted($normalized, $weights, $num_alternatives, $num_criteria) {
    $weighted = array();
    for ($i = 0; $i < $num_alternatives; $i++) {
        for ($j = 0; $j < $num_criteria; $j++) {
            $weighted[$i][$j] = $normalized[$i][$j] * $weights[$j];
        }
    }
    return $weighted;
}

// Fungsi perhitungan S+ dan S-
function calculate_sums($weighted, $types, $num_alternatives, $num_criteria) {
    $benefit_sums = array();
    $cost_sums = array();
    for ($i = 0; $i < $num_alternatives; $i++) {
        $benefit_sums[$i] = 0;
        $cost_sums[$i] = 0;
        for ($j = 0; $j < $num_criteria; $j++) {
            if ($types[$j] == 'benefit') {
                $benefit_sums[$i] += $weighted[$i][$j];
            } else {
                $cost_sums[$i] += $weighted[$i][$j];
            }
        }
    }
    return array($benefit_sums, $cost_sums);
}

// Fungsi perhitungan Qi
function calculate_q($benefit_sums, $cost_sums, $num_alternatives) {
    $Q = array();
    $min_cost = min($cost_sums);
    $sum_costs = array_sum($cost_sums);
    $sum_benefits = array_sum($benefit_sums);
    $sum_min_cost_ratios = 0;
    
    // Menghitung jumlah min(S^-) / S_n^-
    for ($i = 0; $i < $num_alternatives; $i++) {
        $sum_min_cost_ratios += $min_cost / $cost_sums[$i];
    }

    // Menghitung Qi
    for ($i = 0; $i < $num_alternatives; $i++) {
        $Q[$i] = $benefit_sums[$i] + ($min_cost * $sum_costs) / ($cost_sums[$i] * $sum_min_cost_ratios);
    }

    return $Q;
}

// Mendapatkan nilai input
$kriteria = array();
$types = array();
$weights = array();
for ($i = 1; $i <= 5; $i++) {
    $kriteria[] = $_POST["kriteria$i"];
    $types[] = $_POST["type$i"];
    $weights[] = $_POST["bobot$i"];
}

$alternatives = array();
for ($i = 1; $i <= 5; $i++) {
    $alternatives[$i - 1] = array();
    for ($j = 1; $j <= 5; $j++) {
        $alternatives[$i - 1][] = $_POST["alt{$i}_krit{$j}"];
    }
}

// Normalisasi matriks
$normalized = normalize($alternatives, 5, 5);

// Pembobotan
$weighted = weighted($normalized, $weights, 5, 5);

// Perhitungan S+ dan S-
list($benefit_sums, $cost_sums) = calculate_sums($weighted, $types, 5, 5);

// Perhitungan Qi
$Q = calculate_q($benefit_sums, $cost_sums, 5);

// Mengurutkan alternatif berdasarkan Qi
arsort($Q);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil DSS COPRAS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Hasil Perhitungan DSS - Metode COPRAS</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Alternatif</th>
                <th>Qi</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            foreach ($Q as $alt => $q_value) {
                echo "<tr>";
                echo "<td>Alternatif " . ($alt + 1) . "</td>";
                echo "<td>$q_value</td>";
                echo "<td>$rank</td>";
                echo "</tr>";
                $rank++;
            }
            ?>
        </tbody>
    </table>
    <button onclick="history.go(-1);">Kembali</button>
</body>
</html>
