<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'drone_booking_system'); // Adjust credentials as necessary

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    $role = $conn->real_escape_string($_POST['role']); // Get the role from the form

    // Validate form data
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: register.php');
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: register.php');
        exit();
    }

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "An account with this email already exists.";
        header('Location: register.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Registration successful! Please log in.";
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header('Location: register.php');
        exit();
    }
} else {
    header('Location: register.php');
    exit();
}

// Close the database connection
$conn->close();
?>
