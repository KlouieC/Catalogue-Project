<?php 

session_start();
if(!isset($_SESSION['your-random-session-sjfgetwrcvdjdzzz'])){
	header("Location:login.php?refer=insert");
}

include ("../includes/header.php"); ?>


<?php

$podTitle = "";
$hostName = "";
$year = "";
$genre = "";
$episodes = "";
$language = "";
$applePod = "";
$spotify = "";
$googlePod = "";
$youtube = "";
$podUrl = "";
$description = "";
$filename = "";

$msgSuccess = "";
$podTitleMsg = "";
$genreMsg = "";
$podUrlMsg = "";
$descriptionMsg = "";
$valMessage = "";
$strValMessage = "";
$filetype = "";
$id = "";
$allLinks = "";


// This is a PAGESETTER variable. 

if(isset($_GET['id'])) {
    $id = $_GET['id']; 
}

if(!$id) {
    $sql = "SELECT id FROM podcasts_collection LIMIT 1";
    $result = mysqli_query($con, $sql) or die(mysqli_error($con));
    while($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
    }
}

// Step 3:

if (isset($_POST['submit'])) {

    $podTitle = mysqli_real_escape_string($con, trim($_POST['podTitle']));
    $hostName = mysqli_real_escape_string($con, trim($_POST['hostName']));
    $year = mysqli_real_escape_string($con, trim($_POST['year']));
    $genre = mysqli_real_escape_string($con, trim($_POST['genre']));
    $episodes = mysqli_real_escape_string($con, trim($_POST['episodes']));
    $language = mysqli_real_escape_string($con, trim($_POST['language']));
    $podUrl = mysqli_real_escape_string($con, trim($_POST['podUrl']));
    $description = mysqli_real_escape_string($con, trim($_POST['description']));

    $originalsFolder = "../originals/";
    // $filename = $_FILES['myfile']['name'];

    if(isset($_POST['platform_apple'])) {
        $applePod = +($_POST['platform_apple']);
    }

	if($applePod != 1) {
		$applePod = 0;
	}

    if(isset($_POST['platform_spotify'])) {
        $spotify = +($_POST['platform_spotify']);
    }

	if($spotify != 1) {
		$spotify = 0;
	}

    if(isset($_POST['platform_google'])) {
        $googlePod = +($_POST['platform_google']);
    }

	if($googlePod != 1) {
		$googlePod = 0;
	}

    if(isset($_POST['platform_youtube'])) {
        $youtube = +($_POST['platform_youtube']);
    }

	if($youtube != 1) {
		$youtube = 0;
	}


    $valid = 1;
    $msgPreError = "\n<div class=\"alert alert-danger\" role=\"alert\">";
    $msgPreSuccess = "\n<div class=\"alert alert-primary\" role=\"alert\">";
    $msgPost = "\n</div>\n";

    // Podcast Title Validation
    if((strlen($podTitle) < 2) || (strlen($podTitle) > 50)){
        $valid = 0;
        $podTitleMsg = "Please enter a Podcast Title from 2 to 50 characters.";
    }

    // Podcast Genre Validation
    if((strlen($genre) < 4) || (strlen($genre) > 60)){
        $valid = 0;
        $genreMsg = "Please enter a Genre from 4 to 60 characters.";
    }

    // URL Podcast Validation
    if($podUrl != "") {
        $podUrl = filter_var($podUrl, FILTER_SANITIZE_URL); 
        if(!filter_var($podUrl, FILTER_VALIDATE_URL)) {
            $valid = 0;
            $podUrlMsg = "Please enter a valid Podcast URL.";
        }
    } else {
        $valid = 0;
        $podUrlMsg = "Please fill in Podcast URL.";
    }

    //Description Validation
    if((strlen($description) < 10 ) || (strlen($description) > 500)){
        $valid = 0;
        $descriptionMsg = "Please fill in a proper description from 10 to 500 characters.<br>";
    }

    //Image Validation

    // if($_FILES['myfile']['type'] != "image/jpeg"){
    //     $valid = 0;
    //     $valMessage .= "<p>Not a JPG image</p>";
    // }

    // if(($_FILES["myfile"]["type"] == "image/jpeg")||($_FILES["myfile"]["type"] == "image/pjpeg"))
    // {
    //     $filetype = "jpg";
    // }elseif($_FILES["myfile"]["type"] == "image/png"){
    //     $filetype = "png";
    // }else{
    //     $strValMessage .= "Not a jpeg file<br>";
    //     $valid = 0;
    // }


    // if(!($_FILES['myfile']['size'] < 10 * 1024 * 1024)){
    //     $valMessage .= "File is too large<br>";
    //     $valid = 0;
    // }


    // SUCCESS
    if($valid == 1){

		$sql = "UPDATE podcasts_collection SET 
        podTitle = '$podTitle',
        hostName = '$hostName',
        year = '$year',
        genre = '$genre',
        episodes = '$episodes',
        language = '$language',
        podUrl = '$podUrl',
        description = '$description',
		-- filename = '$filename',
        platform_apple = '$applePod',
		platform_spotify = '$spotify',
		platform_google = '$googlePod',
		platform_youtube = '$youtube'
        WHERE id = '$id'";

        mysqli_query($con, $sql) or die(mysqli_error($con));

        $msgSuccess = "Successfully Updated <b>$podTitle</b> Podcasts Details!";
    }
}

// Step 1

if(isset($_GET['id'])) {
    $id = $_GET['id']; 
}

$sql = "SELECT podTitle, id FROM podcasts_collection";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));


while($row = mysqli_fetch_array($result)) {
    $podTitle = $row['podTitle'];
    $podid = $row['id'];

    if ($id === $podid) {
        $podLink = "<a class=\"selected\" href=\"edit.php?id=$podid\">$podTitle 
        </a>";
    } else {
        $podLink = "<a href=\"edit.php?id=$podid\">$podTitle</a>";
    }

    $allLinks .= $podLink;
}


// Step 2: 

$sql = "SELECT * FROM podcasts_collection where id = '$id'";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
while($row = mysqli_fetch_array($result)) {
    $podTitle = $row['podTitle'];
    $hostName = $row['hostName'];
    $year = $row['year'];
    $genre = $row['genre'];
    $episodes = $row['episodes'];
    $language = $row['language'];
	// $filename = $row['filename'];
    $podUrl = $row['podUrl'];
    $description = $row['description'];
    $applePod = $row['platform_apple'];
	$spotify = $row['platform_spotify'];
	$googlePod = $row['platform_google'];
	$youtube = $row['platform_youtube'];
}

?>


<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" name="submit" method="POST" class="my-5">

	<div class="row">
		<div class="col-12 col-lg-8 bg-white p-5 shadow-sm">
			<h2 class="text-center mb-4">Edit Podcast Information</h2>

			<div class="form-group mt-3 required">
                        <label for="podTitle" class="mb-1">Podcast Title:</label>
                        <input type="text" class="form-control" id="podTitle" name="podTitle" value="<?php if($podTitle){echo $podTitle;} ?>">
                        <?php if($podTitleMsg){echo $msgPreError.$podTitleMsg. $msgPost;}?>
            </div>
			<div class="form-group mt-3">
                        <label for="hostName" class="mb-1">Host(s) Name:</label>
                        <input type="text" class="form-control" id="hostName" name="hostName" value="<?php if($hostName){echo $hostName;} ?>">
            </div>

            <div class="form-group mt-3">
                        <label for="year" class="mb-1">Running Since:</label>
                        <input type="text" class="form-control" id="year" name="year" value="<?php if($year){echo $year;} ?>">
            </div>

            <div class="form-group mt-3">
                        <label for="genre" class="mb-1 required">Genre:</label>
                        <input type="text" class="form-control" id="genre" name="genre" value="<?php if($genre){echo $genre;} ?>">
                        <?php if($genreMsg){echo $msgPreError.$genreMsg. $msgPost;}?>
            </div>
            <div class="form-group mt-3">
                        <label for="episodes" class="mb-1">No of Episodes:</label>
                        <input type="text" class="form-control" id="episodes" name="episodes" value="<?php if($episodes){echo $episodes;} ?>">
            </div>

			<div class="form-group mt-3">
                        <label for="language" class="mb-1">Podcast Language:</label>

                        <select name="language" id="language" class="form-control">
                            <option value="">-Select a Language-</option>

                            <option value="English" <?php if(isset($language) && $language == "English"){echo "selected";} ?>>English</option>
                            <option value="English and Filipino" <?php if(isset($language) && $language == "English and Filipino"){echo "selected";} ?>>English & Filipino</option>
                            <option value="English and Korean"  <?php if(isset($language) && $language == "English and Korean"){echo "selected";} ?>>English & Korean</option>
                            <option value="Filipino"  <?php if(isset($language) && $language == "Filipino"){echo "selected";} ?>>Filipino</option>
                            <option value="Korean"  <?php if(isset($language) && $language == "Korean"){echo "selected";} ?>>Korean</option>
                           
                        </select>
            </div>

			<!-- <div class="form-group mt-3">
			            <label for="">Image:</label>
			            <input type="file" name="myfile">
		    </div> -->

			<div class="form-group mt-3">
                    <label for="platform" class="mb-1">Platform:</label>

                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="applePod" name="applePod" value="1" <?php if($applePod == "1"){echo "checked";} ?>>
                            <label class="form-check-label" for="applePod">Apple Podcasts</label>
                        </div>
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="spotify" name="spotify" value="1" <?php if($spotify == "1"){echo "checked";} ?>>
                            <label class="form-check-label" for="spotify">Spotify</label>
                        </div>
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="googlePod" name="googlePod" value="1" <?php if($googlePod == "1"){echo "checked";} ?>>
                            <label class="form-check-label" for="googlePod">Google Podcasts</label>
                        </div>
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="youtube" name="youtube" value="1" <?php if($youtube == "1"){echo "checked";} ?>>
                            <label class="form-check-label" for="youtube">Youtube</label>
                        </div>
            </div>

			<div class="form-group mt-3 required">
                        <label for="podUrl" class="mb-1">Podcast Url:</label>
                        <input type="text" class="form-control" id="podUrl" name="podUrl" value="<?php if($podUrl){echo $podUrl;} ?>">
                        <?php if($podUrlMsg){echo $msgPreError.$podUrlMsg. $msgPost;}?>
            </div>
			<div class="form-group mt-3 required">
                        <label for="description" class="mb-1 required">Description:</label>
						<textarea class="form-control" rows="5" name="description"><?php if($description){echo $description;} ?></textarea>
                        <?php if($descriptionMsg){echo $msgPreError.$descriptionMsg. $msgPost;}?>
            </div>

			<div class="d-flex align-items-center gap-2 my-3">
                <div class="w-100">
                    <input type="submit" name="submit" class="btn btn-info w-100 py-2" value="SUBMIT">
                </div>
                <div class="w-100">
                    <a href="delete.php?id=<?php echo $id ?>" class="btn btn-danger d-block py-2" onclick="return confirmAction()">
                    DELETE
                </a>
                </div>
                <br><br>
            </div>

            <div class="py-2 align-items-center my-3">
                    <a href="insert.php" class="btn btn-dark btn-lg btn-block"> Add Another Podcast
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle pt-1" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </a>
            </div>

			<?php
				if($msgSuccess) {
					echo $msgPreSuccess . $msgSuccess . $msgPost;
				}
			?>
		</div>

		<div class="col-12 col-lg-4">
            <div class="lists bg-white shadow-sm">
                <?php echo $allLinks; ?>
            </div>
        </div>


	</div>
</form>


<!-- Delete Confirmation & Alert -->
<script>
function confirmAction() {
    if (confirm("Are you sure you want to delete this podcast?")) {
        return true;
    } else {
        return false;
    }
} 
</script>

<?php include ("../includes/footer.php"); ?>