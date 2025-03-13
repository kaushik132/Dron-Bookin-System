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

// Check if the drone ID is set
if (!isset($_GET['drone_id'])) {
    header('Location: manage_drones.php');
    exit();
}

$drone_id = intval($_GET['drone_id']);

// Fetch drone details from the database
$sql = "SELECT type, manufacturer, max_load, speed FROM drones WHERE id = $drone_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Drone not found.";
    header('Location: manage_drones.php');
    exit();
}

$drone = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $conn->real_escape_string($_POST['type']);
    $manufacturer = $conn->real_escape_string($_POST['manufacturer']);
    $max_load = $conn->real_escape_string($_POST['max_load']);
    $speed = $conn->real_escape_string($_POST['speed']);

    // Update drone details
    $update_sql = "UPDATE drones SET type = '$type', manufacturer = '$manufacturer', max_load = '$max_load', speed = '$speed' WHERE id = $drone_id";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "Drone updated successfully!";
        header('Location: manage_drones.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating drone: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Drone - Drone Flight Booking System</title>
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
        form input[type="number"] {
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
        <h1>Edit Drone</h1>
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

        <form action="edit_drone.php?drone_id=<?php echo $drone_id; ?>" method="post">
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($drone['type']); ?>" required>

            <label for="manufacturer">Manufacturer:</label>
            <input type="text" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($drone['manufacturer']); ?>" required>

            <label for="max_load">Max Load (kg):</label>
            <input type="number" id="max_load" name="max_load" value="<?php echo htmlspecialchars($drone['max_load']); ?>" step="0.01" required>

            <label for="speed">Speed (km/h):</label>
            <input type="number" id="speed" name="speed" value="<?php echo htmlspecialchars($drone['speed']); ?>" step="0.01" required>

            <button type="submit">Update Drone</button>
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
