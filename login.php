<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'picture_password_db');
if ($conn->connect_error) die(json_encode(['success' => false, 'message' => 'Database connection failed']));

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$sequence = json_encode($data['sequence'] ?? []);

$stmt = $conn->prepare("SELECT sequence FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    if ($row['sequence'] === $sequence) {
        $_SESSION['username'] = $username;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect sequence']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username not found']);
}
$conn->close();
?>