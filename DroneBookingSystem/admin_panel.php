<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Retrieve session data
$user_name = $_SESSION['user_name'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'drone_booking_system'); // Adjust credentials as necessary

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch some statistics or necessary data for the admin dashboard if needed
// Example: Count of users, bookings, drones, etc.
$users_count_sql = "SELECT COUNT(id) AS user_count FROM users";
$users_count_result = $conn->query($users_count_sql);
$users_count = $users_count_result->fetch_assoc()['user_count'];

$bookings_count_sql = "SELECT COUNT(id) AS booking_count FROM bookings";
$bookings_count_result = $conn->query($bookings_count_sql);
$bookings_count = $bookings_count_result->fetch_assoc()['booking_count'];

$drones_count_sql = "SELECT COUNT(id) AS drone_count FROM drones";
$drones_count_result = $conn->query($drones_count_sql);
$drones_count = $drones_count_result->fetch_assoc()['drone_count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Drone Flight Booking System</title>
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

        .admin-container {
            padding: 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }

        .admin-container h2 {
            margin-bottom: 20px;
        }

        .admin-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 40px;
        }

        .admin-stats .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 200px;
            text-align: center;
        }

        .admin-stats .card h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .admin-stats .card p {
            font-size: 1.2em;
            margin: 0;
        }

        .admin-actions {
            display: flex;
            justify-content: space-around;
        }

        .admin-actions .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 200px;
            transition: transform 0.3s ease;
        }

        .admin-actions .card:hover {
            transform: translateY(-10px);
        }

        .admin-actions .card h3 {
            margin: 0 0 20px;
            color: #333;
        }

        .admin-actions .card a {
            display: block;
            padding: 10px;
            background: #6a3093;
            background: linear-gradient(to right, #a044ff, #6a3093);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .admin-actions .card a:hover {
            background: linear-gradient(to right, #6a3093, #a044ff);
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
        .glowing-text{
            animation-name: glow;
            animation-duration: 1s;
            animation-iteration-count: infinite;
            animation-direction: alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0px 0px 5px #fff, 0px 0px 5px #614ad3;
            }

            to {
                text-shadow: 0px 0px 20px #fff, 0px 0px 20px #614ad3;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
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

    <div class="admin-container">
        <h2 class="glowing-text">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        
        <div class="admin-stats">
            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo $users_count; ?></p>
            </div>
            <div class="card">
                <h3>Total Bookings</h3>
                <p><?php echo $bookings_count; ?></p>
            </div>
            <div class="card">
                <h3>Total Drones</h3>
                <p><?php echo $drones_count; ?></p>
            </div>
        </div>

        <div class="admin-actions">
            <div class="card">
                <h3>Manage Users</h3>
                <a href="manage_users.php">Go to Users</a>
            </div>
            <div class="card">
                <h3>Manage Drones</h3>
                <a href="manage_drones.php">Go to Drones</a>
            </div>
            <div class="card">
                <h3>Manage Bookings</h3>
                <a href="manage_bookings.php">Go to Bookings</a>
            </div>
            <div class="card">
                <h3>Air Corridor</h3>
                <a href="add_air_corridor.php">Add Air corridor</a>
            </div>
        </div>
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
