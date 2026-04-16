<?php
$isLocal = $_SERVER['SERVER_NAME'] === 'localhost';


// If - Else para sa render website deploy
if ($isLocal) {
    $host = "localhost";
    $dbname = "social_app";
    $username = "root";
    $password = "";
} else {
    $host = "sql12.freesqldatabase.com";
    $dbname = "sql12821796";
    $username = "sql12821796";
    $password = "ywp6PwMnMD";
}

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("SET time_zone = '+08:00'");

return $conn;