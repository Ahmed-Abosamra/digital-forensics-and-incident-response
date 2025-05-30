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

$servername = "localhost";
$username = "bruteuser";
$password = "N4@vR8!pZq";
$dbname = "bruteflag";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_flag = $_POST['flag_input'];

    if (preg_match('/^[a-z]{3}$/', $input_flag)) {

        $stmt = $conn->prepare("SELECT * FROM flags WHERE LOWER(flag) = LOWER(?)");
        $stmt->bind_param("s", $input_flag);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Correct flag : flag{hello_ctf_world}  🎉🎉🎉 ";
        } else {
            $message = "Wrong flag, try again.";
        }

        $stmt->close();
    } else {
        $message = "Invalid input. Please enter exactly 3 lowercase letters.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flag Checker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Enter the 3-letter (lower-case) flag</h2>
    <form method="POST" action="">
        <input type="text" name="flag_input" maxlength="3" placeholder="Enter the flag" required>
        <input type="submit" value="Check Flag">
    </form>

    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>

    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
