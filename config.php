<?php
// MySQL credentials
define("DBUSER", "root");
define("DBPASSWORD", "root");
define("DBDATABASE", "bnb");
define("DBHOST", "127.0.0.1");

// Function to establish a database connection
function getDBConnection() {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}
?>
