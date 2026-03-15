<?php
$host = '127.0.0.1';
$user = 'root';
$pass = 'root';

mysqli_report(MYSQLI_REPORT_OFF);

echo "Connecting to MySQL server...\n";
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}
echo "Connected successfully.\n";

// Create Database
echo "Creating database 'wms'...\n";
$sql = "CREATE DATABASE IF NOT EXISTS wms";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

$conn->select_db('wms');

// Import SQL
echo "Importing wms.sql...\n";
$sqlFile = file_get_contents('wms.sql');
$queries = explode(';', $sqlFile);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if (!$conn->query($query)) {
             // Ignore errors for comments, empty lines, or "already exists" errors
             $error = $conn->error;
             $errno = $conn->errno;
             if (strpos($query, '--') !== 0 && strpos($query, '/*') !== 0 && $errno !== 1050 && $errno !== 1061) {
                 echo "Error executing query: " . substr($query, 0, 50) . "... -> " . $error . " (Code: $errno)\n";
             }
        }
    }
}
echo "Import complete (check for errors above).\n";

// Create 'wms' user
echo "Creating 'wms' user...\n";
$userSql = "CREATE USER IF NOT EXISTS 'wms'@'%' IDENTIFIED BY 'wms';";
$grantSql = "GRANT ALL PRIVILEGES ON *.* TO 'wms'@'%';";
$flushSql = "FLUSH PRIVILEGES;";

$conn->query($userSql);
$conn->query($grantSql);
$conn->query($flushSql);

$userSqlLocal = "CREATE USER IF NOT EXISTS 'wms'@'localhost' IDENTIFIED BY 'wms';";
$grantSqlLocal = "GRANT ALL PRIVILEGES ON *.* TO 'wms'@'localhost';";

$conn->query($userSqlLocal);
$conn->query($grantSqlLocal);
$conn->query($flushSql);


echo "User 'wms' created/updated and privileges granted.\n";

$conn->close();
?>
