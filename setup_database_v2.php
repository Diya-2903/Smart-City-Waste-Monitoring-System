<?php
// Database setup script using 127.0.0.1
$host = "127.0.0.1";
$user = "root";
$password = "root";

echo "Attempting connection to $host user=$user\n";
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "\n");
}

echo "Connected successfully to MySQL server\n";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS wms";
if (mysqli_query($conn, $sql)) {
    echo "Database 'wms' created successfully or already exists\n";
} else {
    die("Error creating database: " . mysqli_error($conn) . "\n");
}

mysqli_select_db($conn, "wms");

// Read and execute SQL file
$sqlFile = __DIR__ . '/wms.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found: $sqlFile\n");
}

// Read the file
$lines = file($sqlFile);
$templine = '';
$successCount = 0;
$errorCount = 0;

foreach ($lines as $line) {
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

    // Add this line to the current segment
    $templine .= $line;
    
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';') {
        if (mysqli_query($conn, $templine)) {
            $successCount++;
            $templine = '';
        } else {
            // Only report if it's not "already exists" error
            $err = mysqli_error($conn);
            if (strpos($err, "already exists") === false) {
                 echo "Error performing query '<strong>" . substr($templine,0,50) . "...</strong>': " . $err . "\n";
                 $errorCount++;
            }
            $templine = '';
        }
    }
}

echo "Import complete. Success: $successCount, Errors: $errorCount\n";
mysqli_close($conn);
?>
