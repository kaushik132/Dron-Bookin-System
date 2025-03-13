<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Drone Flight Booking System</title>
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
            max-width: 600px;
            margin: auto;
            text-align: center;
        }

        .content h2 {
            margin-bottom: 20px;
        }

        .contact-info {
            margin-bottom: 40px;
        }

        .contact-info p {
            margin: 10px 0;
            font-size: 1.2em;
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

        form input[type="text"], form input[type="email"], form textarea {
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
    </style>
</head>
<body>
    <header>
        <h1>Contact Us</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <div class="content">
        <h2>Get in Touch</h2>
        <div class="contact-info">
            <p>Email: support@dronebookingsystem.com</p>
            <p>Phone: +1-800-555-1234</p>
            <p>Address: 123 Drone Lane, Flight City, FC 45678</p>
        </div>

        <form action="#" method="post">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Drone Flight Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
