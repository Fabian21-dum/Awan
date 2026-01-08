<?php
session_start();
require '../config.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];

    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    if ($stmt->execute([$name])) {
        header("Location: ../categories.php");
        exit;
    }
}