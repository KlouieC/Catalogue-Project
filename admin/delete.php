<?php include("../includes/mysql_connect.php");

$id = "";
if(isset($_GET['id'])) {
    $id = $_GET['id']; 
}

$sql = "DELETE FROM podcasts_collection WHERE id = '$id'";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));

header("Location:edit.php");
?>