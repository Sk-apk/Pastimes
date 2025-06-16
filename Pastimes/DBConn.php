<?php
$server = (string) "localhost"; 
$userName =  (string) "root"; 
$password = (string) ""; 
$database = (string) "clothingstore";
$portNumber = (int) 3306;


// Create connection
$connect =  mysqli_connect($server, $userName, $password, $database, $portNumber);

// Check connection
if ($connect) {
//echo "Connected";
 
}else{

echo "Error! Not connected";

}
?>