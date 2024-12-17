<?php
$hostname = 'localhost';
$username = 'root';
$password ='';
$database = 'ims';
$port=3306;

$conn=new mysqli($hostname, $username, $password, $database, $port);

if($conn  ->connect_error){
    die('connection failed: '.$conn  ->connect_error);
    
}
// echo 'connection successfully';
// $conn->close();
?>