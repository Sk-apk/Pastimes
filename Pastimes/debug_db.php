<?php
    // Rigorous error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    echo "DEBUG_DB_START: Attempting to load DBConn.php<br>"; // Very early debug

    require_once 'DBConn.php';

    echo "DEBUG_DB_END: DBConn.php loaded successfully. Connection status: "; // End debug

    if ($connect) {
        echo "CONNECTED!<br>";
        echo "Database Host Info: " . $connect->host_info . "<br>";
    } else {
        echo "FAILED to connect. MySQLi error: " . $connect->connect_error . "<br>";
    }

    // Attempt a very simple query to ensure connection is live
    $test_query_result = $connect->query("SELECT 1+1 AS test_sum");
    if ($test_query_result) {
        $row = $test_query_result->fetch_assoc();
        echo "Test query (1+1) result: " . $row['test_sum'] . "<br>";
        $test_query_result->close();
    } else {
        echo "Test query FAILED: " . $connect->error . "<br>";
    }

    $connect->close();
    echo "DEBUG_DB_END: Connection closed.<br>";
    ?>
    