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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$servername = "localhost";
$username = "webuser";
$password = "f#9T@q!Z2&";
$dbname = "dfir";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$retrieved_flag = null;
$login_attempted = false;
$login_success = false;
$welcome_user = null;

if (isset($_POST['username']) && isset($_POST['password'])) {
    $login_attempted = true;
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT username, flag FROM users WHERE username='$user' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $retrieved_flag = $row['flag'];
        $welcome_user = $row['username'];
        $login_success = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2> <h3>username of Admin is : aastadmin</h3>
	
    <?php if ($login_success): ?>
        <h3 style="color: green;">✅ Login successful!</h3>
        <?php if ($retrieved_flag): ?>
            <p>Your secret flag is: <strong><?php echo htmlspecialchars($retrieved_flag); ?></strong></p>
        <?php else: ?>
            <p>✔️ Logged in, but no flag assigned to this user.</p>
        <?php endif; ?>
    <?php elseif ($login_attempted): ?>
        <h3 style="color: red;">❌ Invalid credentials</h3>
    <?php endif; ?>

    <form method="POST" action="">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
