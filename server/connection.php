<?php

$conn = mysqli_connect('localhost', 'root', '1234', 'php_project') or die("Couldn't connect to database");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
