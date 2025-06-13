<?php
include 'config.php';

$response = '';

if (isset($_POST['option'])) {
    $option = $conn->real_escape_string($_POST['option']);

    // Update vote count
    $sql = "UPDATE poll_results SET vote_count = vote_count + 1 WHERE option_name = '$option'";
    $conn->query($sql);

    // Get all poll results
    $sql = "SELECT option_name, vote_count FROM poll_results";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $totalVotes = 0;
        $pollData = [];
        while($row = $result->fetch_assoc()) {
            $totalVotes += $row['vote_count'];
            $pollData[] = $row;
        }

        $response .= '<h3>Kết quả bình chọn:</h3>';
        foreach ($pollData as $item) {
            $percentage = ($totalVotes > 0) ? round(($item['vote_count'] / $totalVotes) * 100, 2) : 0;
            $response .= '<div>';
            $response .= '<span>' . htmlspecialchars($item['option_name']) . ': ' . $item['vote_count'] . ' phiếu (' . $percentage . '%)</span>';
            $response .= '<div class="progress-bar" style="width: ' . $percentage . '%;"></div>';
            $response .= '</div>';
        }
    } else {
        $response .= '<p>Không có dữ liệu bình chọn.</p>';
    }
} else {
    $response .= '<p>Vui lòng chọn một lựa chọn để bình chọn.</p>';
}

echo $response;

$conn->close();
?>