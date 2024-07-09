<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSS COPRAS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Decision Support System - COPRAS Method</h1>
    <form action="copras.php" method="post">
        <h2>Kriteria</h2>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <div>
                <label for="kriteria<?= $i ?>">Kriteria <?= $i ?>:</label>
                <input type="text" name="kriteria<?= $i ?>" required>
                <label for="type<?= $i ?>">Type:</label>
                <select name="type<?= $i ?>" required>
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </select>
                <label for="bobot<?= $i ?>">Bobot:</label>
                <input type="number" step="0.01" name="bobot<?= $i ?>" required>
            </div>
        <?php endfor; ?>
        
        <h2>Alternatif</h2>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <div>
                <h3>Alternatif <?= $i ?></h3>
                <?php for ($j = 1; $j <= 5; $j++): ?>
                    <label for="alt<?= $i ?>_krit<?= $j ?>">Kriteria <?= $j ?>:</label>
                    <input type="number" step="0.01" name="alt<?= $i ?>_krit<?= $j ?>" required>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>

        <button type="submit">Hitung</button>
    </form>
</body>
</html>
