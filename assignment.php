<?php
// Database credentials
$servername = "localhost"; // Database server (change if necessary)
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "data"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input to prevent XSS
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = nl2br(htmlspecialchars($_POST['address']));

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (Name, Email, Phone, Address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $address);

    // Execute statement
    if ($stmt->execute()) {
        echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";
        echo "<h1 style='color: #4CAF50; text-align: center;'>Form Submitted Successfully</h1>";
        echo "<p style='font-size: 16px; line-height: 1.6;'><strong>Name:</strong> $name</p>";
        echo "<p style='font-size: 16px; line-height: 1.6;'><strong>Email:</strong> $email</p>";
        echo "<p style='font-size: 16px; line-height: 1.6;'><strong>Phone:</strong> $phone</p>";
        echo "<p style='font-size: 16px; line-height: 1.6;'><strong>Address:</strong> $address</p>";
        echo "</div>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();
} else {
    // Show the form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylish Application Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eaeff3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input, textarea, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        button:active {
            transform: scale(0.98);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#applicationForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.post('', formData, function (response) {
                    $('body').html(response);
                }).fail(function () {
                    alert('An error occurred. Please try again.');
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Online Application Form</h1>
        <form id="applicationForm" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" placeholder="Enter your address" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
<?php
}
?>
