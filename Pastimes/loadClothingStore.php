<?php
// DBConn.php 
$servername = (string) "localhost"; 
$userName =  (string) "root"; 
$password = (string) ""; 
$database = (string) "clothingstore";
$portNumber = (int) 3306;
// Create connection
$connect = new mysqli($servername, $username, $password, $database, $portNumber);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Path to the SQL file
$sqlFile = 'myclothingstore.sql'; 

// Function to execute SQL statements from a file
function executeSqlFromFile($connect, $sqlFile) {
    $file = fopen($sqlFile, 'r');
    if (!$file) {
        die("Could not open SQL file: " . $sqlFile);
    }

    $sql = '';
    while (($line = fgets($file)) !== false) {
        $sql .= $line;
        // Check for end of statement (assuming statements end with ';')
        if (substr(trim($line), -1) == ';') {
            // Execute the query
            if ($connect->query($sql) === TRUE) {
                echo "Query executed successfully: " . $sql . "<br>";
            } else {
                echo "Error executing query: " . $connect->error . "<br>Query: " . $sql . "<br>";
            }
            $sql = ''; // Reset the SQL string for the next statement
        }
    }
    fclose($file);
}

// Drop the database if it exists
$sqlDropDB = "DROP DATABASE IF EXISTS $dbname";
if ($conn->query($sqlDropDB) === TRUE) {
    echo "Database dropped successfully (if it existed)<br>";
} else {
    echo "Error dropping database: " . $conn->error . "<br>";
}

// Create the database
$sqlCreateDB = "CREATE DATABASE $dbname";
if ($conn->query($sqlCreateDB) === TRUE) {
    echo "Database created successfully<br>";
     // Select the database
    $conn->select_db($dbname);

    // Execute the SQL file to create tables
    executeSqlFromFile($conn, $sqlFile);
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}



$conn->close();
?>