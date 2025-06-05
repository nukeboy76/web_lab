<?php
// Helper for MySQLi connection using environment variables
function get_db_connection(): mysqli {
    $mysqli = new mysqli(
        getenv('DB_HOST'),
        getenv('DB_USER'),
        getenv('DB_PASS'),
        getenv('DB_NAME')
    );
    if ($mysqli->connect_error) {
        die('Connection failed: '.$mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}
?>
