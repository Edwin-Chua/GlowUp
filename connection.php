<?php
    //step 1 - create a conenction to the database
    $server = "localhost"; //127.0.0.1
    $user = "root";
    $password = "";
    $database = "glowup";

    $connection = mysqli_connect($server, $user, $password, $database);
    if($connection == false){
        die("Connection failed! " . mysqli_connect_error());
    }// else{
    //     echo "Connection established!";
    // }
?>