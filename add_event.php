<?php
include 'db_connection.php';
$data = json_decode(file_get_contents('php://input'), true);

$room_id = $data['room_id'];
$start = $data['start'];
$end = $data['end'];
$status = $data['status'];

$sql = "INSERT INTO time_slots (room_id, date, start_time, end_time, status) 
        VALUES ('$room_id', CURDATE(), '$start', '$end', '$status')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}

$conn->close();
?>
