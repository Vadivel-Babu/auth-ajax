<?php
mysql://root:EXcBGqVMDNTYIxWrqASbMGFPmBpXckoA@shinkansen.proxy.rlwy.net:39464/railway
$host = "shinkansen.proxy.rlwy.net";
$user = "root";
$pass = "EXcBGqVMDNTYIxWrqASbMGFPmBpXckoA";
$db   = "railway";
$port = 39464;

$conn = new mysqli($host, $user, $pass, $db,$port);

if ($conn->connect_error) {
    die("Database connection failed");
}
?>