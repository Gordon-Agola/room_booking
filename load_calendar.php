<?php
include 'db_connection.php';

// Check if room_id is set and valid
if (!isset($_GET['room_id']) || !is_numeric($_GET['room_id'])) {
    echo "Invalid room ID.";
    exit();
}

$room_id = intval($_GET['room_id']);
$date = date('Y-m-d'); // Current date

// Fetch time slots for the specified room and date
$sql = "SELECT * FROM time_slots WHERE room_id='$room_id' AND date='$date'";
$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}

// Check if any rows are returned
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determine status based on booking status
        $status = ($row['status'] == 'booked') ? 'booked' : 'available';
        echo "<div class='calendar-row'><div class='calendar-cell $status'>" . htmlspecialchars($row['start_time']) . " - " . htmlspecialchars($row['end_time']) . "</div></div>";
    }
} else {
    echo "<div class='calendar-row'><div class='calendar-cell'>No slots available</div></div>";
}

$conn->close();
?>
