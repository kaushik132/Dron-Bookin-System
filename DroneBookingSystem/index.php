<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drone Flight Booking System</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Internal CSS */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lobster', cursive;
            background: url('./images/media.jpg') no-repeat center center fixed;
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

        .hero {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }

        .hero-content h2 {
            font-size: 3em;
            margin: 0;
        }

        .hero-content p {
            font-size: 1.5em;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #FF416C;
            background: linear-gradient(to right, #FF4B2B, #FF416C);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(to right, #FF416C, #FF4B2B);
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
        <h1>Welcome to Drone Flight Booking System</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Your Reliable Drone Flight Booking Service</h2>
            <p>Book your drone flights easily and securely.</p>
            <a href="register.php" class="btn">Get Started</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Drone Flight Booking System. All rights reserved.</p>
    </footer>

    <script>
        // Internal JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const hero = document.querySelector('.hero');
            const heroText = document.querySelector('.hero-content');

            // Simple animation for hero section
            heroText.style.opacity = '0';
            heroText.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                heroText.style.transition = 'all 1s ease';
                heroText.style.opacity = '1';
                heroText.style.transform = 'translateY(0)';
            }, 500);
        });
    </script>
</body>
</html>
