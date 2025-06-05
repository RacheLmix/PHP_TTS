<?php include 'includes/header.php'; ?>

<div class="container glass">
    <h2>Xem nhật ký theo ngày</h2>
    <form method="GET">
        <input type="date" name="date" required>
        <button type="submit">Xem log</button>
    </form>

    <?php
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $filePath = "logs/log_$date.txt";

        if (file_exists($filePath)) {
            echo "<ul class='log-view'>";
            $file = fopen($filePath, "r");
            while (!feof($file)) {
                $line = fgets($file);
                if ($line) {
                    $class = stripos($line, 'thất bại') !== false ? 'error' : '';
                    echo "<li class='$class'>" . htmlspecialchars($line) . "</li>";
                }
            }
            fclose($file);
            echo "</ul>";
        } else {
            echo "<p class='not-found'>Không có nhật ký cho ngày này.</p>";
        }
    }
    ?>
</div>
