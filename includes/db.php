<?php
$conn = new PDO(
    "mysql:host=localhost;dbname=food_helpline",
    "root",
    ""
);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
?>
