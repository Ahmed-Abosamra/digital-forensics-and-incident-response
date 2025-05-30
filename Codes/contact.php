<?php

function logRequest() {
    $logFile = '/opt/lampp/logs/php_requests.log';
    $time = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    $params = ($method === 'POST') ? json_encode($_POST) : json_encode($_GET);

    $entry = "[$time] $ip - $method $uri - $params" . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND);
}
logRequest();


$conn = new mysqli("localhost", "webuser", "f#9T@q!Z2&", "dfir");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['message'])) {
    $message = $_POST['message'];

    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("INSERT INTO messages (content) VALUES (?)");
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT content FROM messages");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<p><a href="index.php">Back to Home</a></p>

<h2>Contact Form</h2>

<p><strong>Make your flag</strong></p>

<form method="POST" action="">
    <textarea name="message" required></textarea><br><br>
    <input type="hidden" name="flag" value="flag_p1{x9k2m1v">
    <input type="submit" value="Send">
</form>

<h3>Messages:</h3>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <?php
        if (stripos($row['content'], 'script') !== false) {
            continue;
        }
    ?>
    <li><?php echo $row['content']; ?></li>
<?php endwhile; ?>
</ul>

</body>
</html>
