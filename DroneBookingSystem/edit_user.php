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

// Check if the user ID is set
if (!isset($_GET['user_id'])) {
    header('Location: manage_users.php');
    exit();
}

$user_id = intval($_GET['user_id']);

// Fetch user details from the database
$sql = "SELECT name, email, role FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    $_SESSION['error'] = "User not found.";
    header('Location: manage_users.php');
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $password = !empty($_POST['password']) ? password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT) : $user['password'];

    // Update user details
    $update_sql = "UPDATE users SET name = '$name', email = '$email', role = '$role', password = '$password' WHERE id = $user_id";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "User updated successfully!";
        header('Location: manage_users.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating user: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Drone Flight Booking System</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Internal CSS */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lobster', cursive;
            background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
            color: #333;
        }

        header {
            background: #6a3093;
            background: linear-gradient(to right, #a044ff, #6a3093);
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            color: #fff;
            font-size: 2.5em;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 20px 0 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #FFD700;
        }

        .content {
            padding: 20px;
            max-width: 600px;
            margin: auto;
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            padding: 10px 15px;
            background: #6a3093;
            background: linear-gradient(to right, #a044ff, #6a3093);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: linear-gradient(to right, #6a3093, #a044ff);
        }

        footer {
            background: #222;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
            width: 100%;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

        .error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit User</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="flight_booking.php">Book a Flight</a></li>
                <li><a href="flight_status.php">View Flight Status</a></li>
                <li><a href="account.php">Manage Account</a></li>
                <li><a href="admin_panel.php">Admin Panel</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="content">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
                ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="edit_user.php?user_id=<?php echo $user_id; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>

            <label for="password">New Password (leave blank if not changing):</label>
            <input type="password" id="password" name="password">

            <button type="submit">Update User</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Drone Flight Booking System. All rights reserved.</p>
    </footer>

    <script>
        // Internal JavaScript for any additional dynamic behavior if needed
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
