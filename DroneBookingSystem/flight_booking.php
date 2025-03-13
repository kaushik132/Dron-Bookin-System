<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'drone_booking_system'); // Adjust credentials as necessary

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available drones from the database
$drone_sql = "SELECT id, type, manufacturer, max_load FROM drones";
$drone_result = $conn->query($drone_sql);

// Fetch available air corridors from the database
$corridor_sql = "SELECT id, start_point, end_point, height FROM air_corridors WHERE status = 'available'";
$corridor_result = $conn->query($corridor_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $drone_id = intval($_POST['drone_id']);
    $corridor_id = intval($_POST['corridor_id']);
    $load_weight = floatval($_POST['load_weight']);
    $risk_level = $conn->real_escape_string($_POST['risk_level']);
    $urgency = $conn->real_escape_string($_POST['urgency']);

    // Insert booking into the database
    $insert_sql = "INSERT INTO bookings (user_id, drone_id, corridor_id, load_weight, risk_level, urgency) 
                   VALUES ('$user_id', '$drone_id', '$corridor_id', '$load_weight', '$risk_level', '$urgency')";
    if ($conn->query($insert_sql) === TRUE) {
        $_SESSION['message'] = "Flight booked successfully!";
        header('Location: flight_status.php');
        exit();
    } else {
        $_SESSION['error'] = "Error booking flight: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Flight - Drone Flight Booking System</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Internal CSS */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lobster', cursive;
            background: url('./images/backgound2.webp') no-repeat center center fixed;
            background-size: cover;
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
            max-width: 800px;
            margin: auto;
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: left;
            opacity: 90%;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        form select,
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
        <h1>Book a Flight</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="flight_booking.php">Book a Flight</a></li>
                <li><a href="flight_status.php">View Flight Status</a></li>
                <li><a href="account.php">Manage Account</a></li>
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

        <form action="flight_booking.php" method="post">
            <label for="drone_id">Select Drone:</label>
            <select id="drone_id" name="drone_id" required>
                <option value="" disabled selected>Select a drone</option>
                <?php while ($drone = $drone_result->fetch_assoc()): ?>
                    <option value="<?php echo $drone['id']; ?>">
                        <?php echo htmlspecialchars($drone['type']) . " - " . htmlspecialchars($drone['manufacturer']) . " (Max Load: " . htmlspecialchars($drone['max_load']) . " kg)"; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="corridor_id">Select Air Corridor:</label>
            <select id="corridor_id" name="corridor_id" required>
                <option value="" disabled selected>Select an air corridor</option>
                <?php while ($corridor = $corridor_result->fetch_assoc()): ?>
                    <option value="<?php echo $corridor['id']; ?>">
                        <?php echo htmlspecialchars($corridor['start_point']) . " to " . htmlspecialchars($corridor['end_point']) . " (Height: " . htmlspecialchars($corridor['height']) . " m)"; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="load_weight">Load Weight (kg):</label>
            <input type="number" id="load_weight" name="load_weight" step="0.01" required>

            <label for="risk_level">Risk Level:</label>
            <select id="risk_level" name="risk_level" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>

            <label for="urgency">Urgency:</label>
            <select id="urgency" name="urgency" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>

            <button type="submit">Book Flight</button>
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
