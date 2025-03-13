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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Drone Flight Booking System</title>
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

        .dashboard-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 70vh;
            text-align: center;
        }

        .dashboard-container .card {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 250px;
            transition: transform 0.3s ease;
        }

        .dashboard-container .card:hover {
            transform: translateY(-10px);
        }

        .dashboard-container .card h3 {
            margin: 0 0 20px;
            color: #333;
        }

        .dashboard-container .card a {
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

        .dashboard-container .card a:hover {
            background: linear-gradient(to right, #6a3093, #a044ff);
        }

        footer {
            background: #222;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
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

    <div class="dashboard-container">
        <div class="card">
            <h3>Book a Flight</h3>
            <a href="flight_booking.php">Go to Booking</a>
        </div>
        <div class="card">
            <h3>View Flight Status</h3>
            <a href="flight_status.php">Check Status</a>
        </div>
        <div class="card">
            <h3>Manage Account</h3>
            <a href="account.php">Edit Account</a>
        </div>
        <?php if ($user_role === 'admin'): ?>
            <div class="card">
                <h3>Admin Panel</h3>
                <a href="admin_panel.php">Go to Admin Panel</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Drone Flight Booking System. All rights reserved.</p>
    </footer>

    <script>
        // Internal JavaScript for animations or additional logic
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseover', () => {
                card.style.transform = 'translateY(-10px)';
            });
            card.addEventListener('mouseout', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
