<?php
include 'db_connection.php';

$room_id = $_GET['room_id'];

$sql = "SELECT id, 
               DATE_FORMAT(start_time, '%Y-%m-%dT%H:%i:%s') AS start,
               DATE_FORMAT(end_time, '%Y-%m-%dT%H:%i:%s') AS end,
               status
        FROM time_slots
        WHERE room_id = '$room_id'";

$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['status'] == 'booked' ? 'Booked' : 'Available',
        'start' => $row['start'],
        'end' => $row['end'],
        'backgroundColor' => $row['status'] == 'booked' ? '#ff9f9f' : '#a0e0a0'
    ];
}

header('Content-Type: application/json');
echo json_encode($events);

$conn->close();
?>
