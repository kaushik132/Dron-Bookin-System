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

// Handle booking deletion if the delete button is clicked
if (isset($_GET['delete_booking'])) {
    $booking_id_to_delete = intval($_GET['delete_booking']);
    $delete_sql = "DELETE FROM bookings WHERE id = $booking_id_to_delete";
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['message'] = "Booking deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting booking: " . $conn->error;
    }
    header('Location: manage_bookings.php');
    exit();
}

// Handle booking approval if the approve button is clicked
if (isset($_GET['approve_booking'])) {
    $booking_id_to_approve = intval($_GET['approve_booking']);
    $approve_sql = "UPDATE bookings SET status = 'approved' WHERE id = $booking_id_to_approve";
    if ($conn->query($approve_sql) === TRUE) {
        $_SESSION['message'] = "Booking approved successfully.";
    } else {
        $_SESSION['error'] = "Error approving booking: " . $conn->error;
    }
    header('Location: manage_bookings.php');
    exit();
}

// Fetch all bookings from the database
$sql = "SELECT bookings.id, users.name as user_name, drones.type as drone_type, air_corridors.start_point, air_corridors.end_point, bookings.load_weight, bookings.risk_level, bookings.urgency, bookings.status 
        FROM bookings 
        JOIN users ON bookings.user_id = users.id 
        JOIN drones ON bookings.drone_id = drones.id 
        JOIN air_corridors ON bookings.corridor_id = air_corridors.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Drone Flight Booking System</title>
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
            max-width: 1000px;
            margin: auto;
            text-align: center;
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

        footer {
            background-color: rgba(0, 0, 0, 0.7); /* Same styling as header */
            padding: 10px;
            text-align: center;
            width: 100%;
            position: absolute;
            bottom: 0;
            color: white;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Bookings</h1>
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

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Drone</th>
                    <th>Route</th>
                    <th>Load (kg)</th>
                    <th>Risk Level</th>
                    <th>Urgency</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($booking = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['drone_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['start_point'] . ' to ' . $booking['end_point']); ?></td>
                            <td><?php echo htmlspecialchars($booking['load_weight']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($booking['risk_level'])); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($booking['urgency'])); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($booking['status'])); ?></td>
                            <td class="actions">
                                <a href="#" class="edit" data-id="<?php echo $booking['id']; ?>" data-drone="<?php echo htmlspecialchars($booking['drone_type']); ?>" data-route="<?php echo htmlspecialchars($booking['start_point'] . ' to ' . $booking['end_point']); ?>" data-load="<?php echo $booking['load_weight']; ?>" data-risk="<?php echo $booking['risk_level']; ?>" data-urgency="<?php echo $booking['urgency']; ?>" data-status="<?php echo $booking['status']; ?>">Edit</a>
                                <a href="manage_bookings.php?approve_booking=<?php echo $booking['id']; ?>">Approve</a>
                                <a href="manage_bookings.php?delete_booking=<?php echo $booking['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 Drone Flight Booking System. All rights reserved.</p>
    </footer>

    <!-- The Modal -->
    <div id="editModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Booking</h2>
            <form action="edit_booking.php" method="post">
                <input type="hidden" id="edit_id" name="id">
                <label for="edit_drone">Drone:</label>
                <input type="text" id="edit_drone" name="drone_type" readonly>

                <label for="edit_route">Route:</label>
                <input type="text" id="edit_route" name="route" readonly>

                <label for="edit_load">Load Weight (kg):</label>
                <input type="number" id="edit_load" name="load_weight" step="0.01" required>

                <label for="edit_risk">Risk Level:</label>
                <select id="edit_risk" name="risk_level" required>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                <label for="edit_urgency">Urgency:</label>
                <select id="edit_urgency" name="urgency" required>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                <label for="edit_status">Status:</label>
                <select id="edit_status" name="status" required>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                </select>

                <button type="submit">Update Booking</button>
            </form>
        </div>

    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("editModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the edit button, open the modal 
        var editButtons = document.getElementsByClassName("edit");
        for (var i = 0; i < editButtons.length; i++) {
            editButtons[i].onclick = function() {
                modal.style.display = "block";
                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('edit_drone').value = this.dataset.drone;
                document.getElementById('edit_route').value = this.dataset.route;
                document.getElementById('edit_load').value = this.dataset.load;
                document.getElementById('edit_risk').value = this.dataset.risk;
                document.getElementById('edit_urgency').value = this.dataset.urgency;
                document.getElementById('edit_status').value = this.dataset.status;
            }
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
