<?php
include 'config.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM doctors WHERE id = $id");
header("Location: manage-doctors.php");
?>
