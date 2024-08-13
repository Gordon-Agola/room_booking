<?php
include 'db_connection.php';
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];

$sql = "DELETE FROM time_slots WHERE id = '$id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}

$conn->close();
?>
