<?php
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

function weighted($normalized, $weights, $num_alternatives, $num_criteria) {
    $weighted = array();
    for ($i = 0; $i < $num_alternatives; $i++) {
        for ($j = 0; $j < $num_criteria; $j++) {
            $weighted[$i][$j] = $normalized[$i][$j] * $weights[$j];
        }
    }
    return $weighted;
}

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

function calculate_q($benefit_sums, $cost_sums, $num_alternatives) {
    $Q = array();
    $min_cost = min($cost_sums);
    $sum_benefits = array_sum($benefit_sums);
    for ($i = 0; $i < $num_alternatives; $i++) {
        $Q[$i] = $benefit_sums[$i] + ($min_cost * $sum_benefits / $cost_sums[$i]);
    }
    return $Q;
}

// Get input values
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

// Normalize matrix
$normalized = normalize($alternatives, 5, 5);

// Apply weights
$weighted = weighted($normalized, $weights, 5, 5);

// Calculate S+ and S-
list($benefit_sums, $cost_sums) = calculate_sums($weighted, $types, 5, 5);

// Calculate Q
$Q = calculate_q($benefit_sums, $cost_sums, 5);

// Sort alternatives by Q
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
