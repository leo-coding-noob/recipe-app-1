<?php
include 'config.php';

if(!isset($_GET['id'])){
    die("Recipe ID not provided.");
}

$id = intval($_GET['id']);

// Delete recipe
$sql = "DELETE FROM recipes WHERE id=$id";

if($conn->query($sql) === TRUE){
    header("Location: index.php");
    exit;
} else {
    die("Error deleting recipe: " . $conn->error);
}
?>