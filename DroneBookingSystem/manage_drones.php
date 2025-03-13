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

// Handle adding a new drone
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_drone'])) {
    $type = $conn->real_escape_string($_POST['type']);
    $manufacturer = $conn->real_escape_string($_POST['manufacturer']);
    $max_load = $conn->real_escape_string($_POST['max_load']);
    $speed = $conn->real_escape_string($_POST['speed']);

    $insert_sql = "INSERT INTO drones (type, manufacturer, max_load, speed) VALUES ('$type', '$manufacturer', '$max_load', '$speed')";
    if ($conn->query($insert_sql) === TRUE) {
        $_SESSION['message'] = "Drone added successfully.";
    } else {
        $_SESSION['error'] = "Error adding drone: " . $conn->error;
    }
    header('Location: manage_drones.php');
    exit();
}

// Handle drone deletion if the delete button is clicked
if (isset($_GET['delete_drone'])) {
    $drone_id_to_delete = intval($_GET['delete_drone']);
    $delete_sql = "DELETE FROM drones WHERE id = $drone_id_to_delete";
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['message'] = "Drone deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting drone: " . $conn->error;
    }
    header('Location: manage_drones.php');
    exit();
}

// Fetch all drones from the database
$sql = "SELECT id, type, manufacturer, max_load, speed FROM drones";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Drones - Drone Flight Booking System</title>
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
            max-width: 800px;
            margin: auto;
            text-align: center;
        }

        .expandable-form {
            display: none;
            margin-bottom: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .expandable-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .expandable-form input[type="text"],
        .expandable-form input[type="number"],
        .expandable-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .expandable-form button {
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

        .expandable-form button:hover {
            background: linear-gradient(to right, #6a3093, #a044ff);
        }

        .content table {
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

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .actions a {
            padding: 5px 10px;
            background: #6a3093;
            background: linear-gradient(to right, #a044ff, #6a3093);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
            transition: background 0.3s ease;
        }

        .actions a:hover {
            background: linear-gradient(to right, #6a3093, #a044ff);
        }

        .actions .delete {
            background-color: #e74c3c;
            background-image: linear-gradient(to right, #e74c3c, #c0392b);
        }

        .actions .delete:hover {
            background-color: #c0392b;
            background-image: linear-gradient(to right, #c0392b, #e74c3c);
        }

        .toggle-button {
            margin-bottom: 20px;
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

        .toggle-button:hover {
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
        <h1>Manage Drones</h1>
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

        <button class="toggle-button" onclick="toggleForm()">Add New Drone</button>

        <div class="expandable-form" id="addDroneForm">
            <form action="manage_drones.php" method="post">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" required>

                <label for="manufacturer">Manufacturer:</label>
                <input type="text" id="manufacturer" name="manufacturer" required>

                <label for="max_load">Max Load (kg):</label>
                <input type="number" id="max_load" name="max_load" step="0.01" required>

                <label for="speed">Speed (km/h):</label>
                <input type="number" id="speed" name="speed" step="0.01" required>

                <button type="submit" name="add_drone">Add Drone</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Manufacturer</th>
                    <th>Max Load (kg)</th>
                    <th>Speed (km/h)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($drone = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($drone['id']); ?></td>
                            <td><?php echo htmlspecialchars($drone['type']); ?></td>
                            <td><?php echo htmlspecialchars($drone['manufacturer']); ?></td>
                            <td><?php echo htmlspecialchars($drone['max_load']); ?></td>
                            <td><?php echo htmlspecialchars($drone['speed']); ?></td>
                            <td class="actions">
                                <a href="edit_drone.php?drone_id=<?php echo $drone['id']; ?>">Edit</a>
                                <a href="manage_drones.php?delete_drone=<?php echo $drone['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this drone?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No drones found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 Drone Flight Booking System. All rights reserved.</p>
    </footer>

    <script>
        // Internal JavaScript for toggling the expandable form
        function toggleForm() {
            var form = document.getElementById('addDroneForm');
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
