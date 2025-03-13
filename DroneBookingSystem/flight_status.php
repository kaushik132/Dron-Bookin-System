<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Retrieve session data
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id']; // User ID is used to fetch only the user's bookings

// Database connection
$conn = new mysqli('localhost', 'root', '', 'drone_booking_system'); // Adjust credentials as necessary

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user-specific bookings from database
$sql = "SELECT bookings.id, drones.type as drone_type, air_corridors.start_point, air_corridors.end_point, bookings.load_weight, bookings.risk_level, bookings.urgency, bookings.status 
        FROM bookings 
        JOIN drones ON bookings.drone_id = drones.id 
        JOIN air_corridors ON bookings.corridor_id = air_corridors.id 
        WHERE bookings.user_id = $user_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Status - Drone Flight Booking System</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Internal CSS */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lobster', cursive;
            background: url('./images/bacjground3.png') no-repeat center center fixed;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background: #6a3093;
            background: linear-gradient(to right, #a044ff, #6a3093);
            color: #fff;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7); /* Same styling as header */
            padding: 10px;
            text-align: center;
            width: 100%;
            position: absolute;
            bottom: 0;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Flight Status, <?php echo htmlspecialchars($user_name); ?></h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="flight_booking.php">Book a Flight</a></li>
                <li><a href="flight_status.php">View Flight Status</a></li>
                <li><a href="account.php">Manage Account</a></li>
                <?php if ($user_role === 'admin'): ?>
                    <li><a href="admin_panel.php">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="content">
        <h2>Your Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Drone</th>
                    <th>Route</th>
                    <th>Load (kg)</th>
                    <th>Risk Level</th>
                    <th>Urgency</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($booking = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['drone_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['start_point'] . ' to ' . $booking['end_point']); ?></td>
                            <td><?php echo htmlspecialchars($booking['load_weight']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($booking['risk_level'])); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($booking['urgency'])); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($booking['status'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No flights found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 Drone Flight Booking System. All rights reserved.</p>
    </footer>

    <script>
        // Internal JavaScript for additional dynamic behavior if needed
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
