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

// Handle adding a new air corridor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_corridor'])) {
    $start_point = $conn->real_escape_string($_POST['start_point']);
    $end_point = $conn->real_escape_string($_POST['end_point']);
    $height = floatval($_POST['height']);
    $status = $conn->real_escape_string($_POST['status']);

    $insert_sql = "INSERT INTO air_corridors (start_point, end_point, height, status) 
                   VALUES ('$start_point', '$end_point', '$height', '$status')";
    if ($conn->query($insert_sql) === TRUE) {
        $_SESSION['message'] = "Air corridor added successfully!";
    } else {
        $_SESSION['error'] = "Error adding air corridor: " . $conn->error;
    }
    header('Location: add_air_corridor.php');
    exit();
}

// Handle editing an air corridor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_corridor'])) {
    $id = intval($_POST['id']);
    $start_point = $conn->real_escape_string($_POST['edit_start_point']);
    $end_point = $conn->real_escape_string($_POST['edit_end_point']);
    $height = floatval($_POST['edit_height']);
    $status = $conn->real_escape_string($_POST['edit_status']);

    $update_sql = "UPDATE air_corridors SET start_point = '$start_point', end_point = '$end_point', height = '$height', status = '$status' WHERE id = $id";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "Air corridor updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating air corridor: " . $conn->error;
    }
    header('Location: add_air_corridor.php');
    exit();
}

// Handle deleting an air corridor
if (isset($_GET['delete_corridor'])) {
    $id = intval($_GET['delete_corridor']);
    $delete_sql = "DELETE FROM air_corridors WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['message'] = "Air corridor deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting air corridor: " . $conn->error;
    }
    header('Location: add_air_corridor.php');
    exit();
}

// Fetch all air corridors from the database
$corridor_sql = "SELECT * FROM air_corridors";
$corridor_result = $conn->query($corridor_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Air Corridor - Drone Flight Booking System</title>
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
        <h1>Add Air Corridor</h1>
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

        <button class="toggle-button" onclick="toggleForm()">Add New Air Corridor</button>

        <div class="expandable-form" id="addCorridorForm">
            <form action="add_air_corridor.php" method="post">
                <label for="start_point">Start Point:</label>
                <input type="text" id="start_point" name="start_point" required>

                <label for="end_point">End Point:</label>
                <input type="text" id="end_point" name="end_point" required>

                <label for="height">Height (m):</label>
                <input type="number" id="height" name="height" step="0.01" required>

                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                </select>

                <button type="submit" name="add_corridor">Add Air Corridor</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Start Point</th>
                    <th>End Point</th>
                    <th>Height (m)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($corridor_result->num_rows > 0): ?>
                    <?php while($corridor = $corridor_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($corridor['id']); ?></td>
                            <td><?php echo htmlspecialchars($corridor['start_point']); ?></td>
                            <td><?php echo htmlspecialchars($corridor['end_point']); ?></td>
                            <td><?php echo htmlspecialchars($corridor['height']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($corridor['status'])); ?></td>
                            <td class="actions">
                                <a href="#" class="edit" data-id="<?php echo $corridor['id']; ?>" data-start="<?php echo htmlspecialchars($corridor['start_point']); ?>" data-end="<?php echo htmlspecialchars($corridor['end_point']); ?>" data-height="<?php echo $corridor['height']; ?>" data-status="<?php echo $corridor['status']; ?>">Edit</a>
                                <a href="add_air_corridor.php?delete_corridor=<?php echo $corridor['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this corridor?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No air corridors found.</td>
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
            <h2>Edit Air Corridor</h2>
            <form action="add_air_corridor.php" method="post">
                <input type="hidden" id="edit_id" name="id">
                <label for="edit_start_point">Start Point:</label>
                <input type="text" id="edit_start_point" name="edit_start_point" required>

                <label for="edit_end_point">End Point:</label>
                <input type="text" id="edit_end_point" name="edit_end_point" required>

                <label for="edit_height">Height (m):</label>
                <input type="number" id="edit_height" name="edit_height" step="0.01" required>

                <label for="edit_status">Status:</label>
                <select id="edit_status" name="edit_status" required>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                </select>

                <button type="submit" name="edit_corridor">Update Air Corridor</button>
            </form>
        </div>

    </div>

    <script>
        // Internal JavaScript for toggling the expandable form
        function toggleForm() {
            var form = document.getElementById('addCorridorForm');
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

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
                document.getElementById('edit_start_point').value = this.dataset.start;
                document.getElementById('edit_end_point').value = this.dataset.end;
                document.getElementById('edit_height').value = this.dataset.height;
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
