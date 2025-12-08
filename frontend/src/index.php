<?php
// NEW FOR DOCKER + MYSQL
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'ideasuser';
$pass = getenv('DB_PASSWORD') ?: 'ideaspass';
$dbname = getenv('DB_NAME') ?: 'ideasdb';

// Connect to MySQL
$mysqli = new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Create table if not exists
$mysqli->query("
CREATE TABLE IF NOT EXISTS EightMinuteRequests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    gender VARCHAR(50),
    mobile VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name   = trim($_POST["name"]);
    $gender = trim($_POST["gender"]);
    $mobile = trim($_POST["mobile"]);

    if ($name === "" || $gender === "" || $mobile === "") {
        $message = "Please fill all the fields.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO EightMinuteRequests (name, gender, mobile) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $gender, $mobile);

        if ($stmt->execute()) {
            $message = "Your 8 minutes request has been received.";
        } else {
            $message = "Error saving your request.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>8 Minutes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: #111827;
            padding: 24px 32px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            max-width: 420px;
            width: 100%;
        }
        h1 {
            margin-top: 0;
            font-size: 32px;
            text-align: center;
            color: #38bdf8;
        }
        p.slogan {
            text-align: center;
            font-size: 14px;
            color: #e5e7eb;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }
        input, select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #374151;
            background: #020617;
            color: #f9fafb;
            margin-bottom: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            border-radius: 9999px;
            border: none;
            background: #38bdf8;
            color: #0f172a;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
        }
        button:hover {
            background: #0ea5e9;
        }
        .message {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
            color: #facc15;
        }
        .contact {
            margin-top: 18px;
            text-align: center;
            font-size: 13px;
            color: #9ca3af;
        }
        .phone {
            font-weight: bold;
            color: #f97316;
        }
    </style>
</head>
<body>
<div class="card">
    <h1>8 Minutes</h1>
    <p class="slogan">
        When life feels heavy, talk for 8 minutes. We listen.
    </p>

    <?php if ($message !== ""): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Your name</label>
        <input id="name" name="name" placeholder="Enter your name" required />

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">-- Select --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="mobile">Mobile number</label>
        <input id="mobile" name="mobile" placeholder="Enter your mobile" required />

        <button type="submit">I want 8 minutes</button>
    </form>

    <div class="contact">
        Or reach us directly at<br />
        <span class="phone">044 - 1040880</span>
    </div>
</div>
</body>
</html>

