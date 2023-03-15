<?php include ("includes/header.php");

$searchterm = "";

?>

<h1 class="display-5 fw-bold text-uppercase text-dark">Search</h1>
<br><br>
        
<?php 

if(isset($_POST['searchterm']) && strlen($_POST['searchterm']) > 2):
$searchterm = $_POST['searchterm'];

$sql = "SELECT * FROM  podcasts_collection WHERE podTitle LIKE '%$searchterm%'
    OR hostName LIKE '%$searchterm%' OR description LIKE '%$searchterm%'";

$result = mysqli_query($con, $sql);
?>

    <!-- Here we write the beginning of the while loop -->
    <?php while ($row = mysqli_fetch_array($result)): ?>

    <p><a href="output.php?id=<?php echo $row['id']?>"><?php echo $row['podTitle'] ?></a></p> 

    <?php endwhile; ?>       
 
<?php endif; ?>



<form action="<?php echo BASE_URL ?>search.php" method="post">

    <div class="form-group required">
		<label for="searchterm">SEARCHTERM:</label>
		<input type="text" id="podcast"  class="form-control" name="searchterm" value="<?php echo $searchterm; ?>">
		
	</div>

    <div class="form-group">
		<label for="submit"></label>
		<input type="submit" name="mysubmit" class="btn btn-info" value="Submit">
	</div>

</form>


<!-- <?php include ("includes/footer.php"); ?> -->