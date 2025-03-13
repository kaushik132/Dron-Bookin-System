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
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Validate form data
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
        header('Location: login.php');
        exit();
    }

    // Check if user exists
    $sql = "SELECT id, name, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];  // Role is retrieved from the database

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header('Location: admin_panel.php');
            } else {
                header('Location: dashboard.php');
            }
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}

// Close the database connection
$conn->close();
?>
