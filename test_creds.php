<?php
$hosts = ['127.0.0.1', 'localhost', '::1'];
$users = ['root', 'admin'];
$passwords = ['', 'root', 'admin', 'password'];

foreach ($hosts as $h) {
    foreach ($users as $u) {
        foreach ($passwords as $p) {
            try {
                echo "Trying $h | $u | $p ... ";
                $conn = @mysqli_connect($h, $u, $p, 'wms');
                if ($conn) {
                    echo "SUCCESS!\n";
                    echo "Use: Host=$h, User=$u, Pass=$p\n";
                    exit(0);
                } else {
                    echo "Failed (" . mysqli_connect_error() . ")\n";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }
}
echo "All attempts failed.\n";
?>
