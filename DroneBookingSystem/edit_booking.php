<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'drone_booking_system'); // Adjust credentials as necessary

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = intval($_POST['id']);
    $load_weight = floatval($_POST['load_weight']);
    $risk_level = $conn->real_escape_string($_POST['risk_level']);
    $urgency = $conn->real_escape_string($_POST['urgency']);
    $status = $conn->real_escape_string($_POST['status']);

    // Update the booking in the database
    $update_sql = "UPDATE bookings 
                   SET load_weight = '$load_weight', risk_level = '$risk_level', urgency = '$urgency', status = '$status'
                   WHERE id = $booking_id";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "Booking updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating booking: " . $conn->error;
    }
    
    // Redirect back to the manage_bookings.php page
    header('Location: manage_bookings.php');
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header('Location: manage_bookings.php');
    exit();
}

?>

