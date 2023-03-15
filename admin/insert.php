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

$msgSuccess = "";
$podTitleMsg = "";
$genreMsg = "";
$podUrlMsg = "";
$descriptionMsg = "";
$valMessage = "";
$strValMessage = "";
$filetype = "";

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
    $filename = $_FILES['myfile']['name'];

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

    if($_FILES['myfile']['type'] != "image/jpeg"){
        $valid = 0;
        $valMessage .= "<p>Not a JPG image</p>";
    }

    if(($_FILES["myfile"]["type"] == "image/jpeg")||($_FILES["myfile"]["type"] == "image/pjpeg"))
    {
        $filetype = "jpg";
    }elseif($_FILES["myfile"]["type"] == "image/png"){
        $filetype = "png";
    }else{
        $strValMessage .= "Not a jpeg file<br>";
        $valid = 0;
    }


    if(!($_FILES['myfile']['size'] < 10 * 1024 * 1024)){
        $valMessage .= "File is too large<br>";
        $valid = 0;
    }

    //Generate Unique Filename

		$newFileName = "img" .md5(uniqid()) . "." . $filetype;

        if($valid == 1) { 

            if(move_uploaded_file($_FILES['myfile']['tmp_name'], $originalsFolder . $_FILES['myfile']['name'])){
    
                $thisFile = $originalsFolder . basename($_FILES['myfile']['name']);
    
                if($filetype == "png"){
                    createSquareImageCopyPNG($thisFile, "../thumbs200/", 200);
                    createSquareImageCopyPNG($thisFile, "../thumbs50", 50);
                    createThumbnail($thisFile, "../display/", 400);
                }else{
                    createSquareImageCopy($thisFile, "../thumbs200/", 200);
                    createSquareImageCopy($thisFile, "../thumbs50", 50);
                    createThumbnail($thisFile, "../display/", 400);
                }

                mysqli_query($con, "INSERT INTO podcasts_collection (podTitle, hostName, year, genre, episodes, language, platform_apple, platform_spotify, platform_google, platform_youtube, filename, podUrl, description) VALUES('$podTitle','$hostName','$year', '$genre','$episodes','$language', '$applePod', '$spotify', '$googlePod','$youtube', '$filename', '$podUrl', '$description')") or die(mysqli_error($con));

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
                $filename = $_FILES['myfile']['name'];

                $valMessage = "The inserted information for" . $_FILES['myfile']['name'] . "has been successfully uploaded!";

            }
        }
}// End if-submit


function createThumbnail($file, $folder, $newwidth){

    list($width, $height) = getimagesize($file);
    $imgRatio = $width/$height;
    $newheight = $newwidth / $imgRatio;

    $thumb = imagecreatetruecolor($newwidth, $newheight);

    $source = imagecreatefromjpeg($file);

    imagecopyresampled($thumb, $source, 0,0,0,0, $newwidth, $newheight, $width, $height);
    $newFileName = $folder . basename($_FILES['myfile']['name']);
    imagejpeg($thumb, $newFileName, 80);
    imagedestroy($thumb);
    imagedestroy($source);
}

function createSquareImageCopy($file, $folder, $newWidth){

    $thumb_width = $newWidth;
    $thumb_height = $newWidth;// tweak this for ratio

    list($width, $height) = getimagesize($file);

    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;

    if($original_aspect >= $thumb_aspect) {
       // If image is wider than thumbnail (in aspect ratio sense)
       $new_height = $thumb_height;
       $new_width = $width / ($height / $thumb_height);
    } else {
       // If the thumbnail is wider than the image
       $new_width = $thumb_width;
       $new_height = $height / ($width / $thumb_width);
    }

    $source = imagecreatefromjpeg($file);
    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

    // Resize and crop
    imagecopyresampled($thumb,
                       $source,0 - ($new_width - $thumb_width) / 2, 
                       0 - ($new_height - $thumb_height) / 2, 
                       0, 0,
                       $new_width, $new_height,
                       $width, $height);
   
    $newFileName = $folder. "/" .basename($file);
    imagejpeg($thumb, $newFileName, 80);
}

function createSquareImageCopyPNG($file, $folder, $newWidth){
    $thumb_width = $newWidth;
    $thumb_height = $newWidth;

    list($width, $height) = getimagesize($file);

    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;

   if($original_aspect >= $thumb_aspect) {
       // If image is wider than thumbnail (in aspect ratio sense)
       $new_height = $thumb_height;
       $new_width = $width / ($height / $thumb_height);
    } else {
       // If the thumbnail is wider than the image
       $new_width = $thumb_width;
       $new_height = $height / ($width / $thumb_width);
    }

    $source = imagecreatefrompng($file);
    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    imagealphablending($thumb,false);
    // Resize and crop
    imagecopyresampled($thumb,
                       $source,0 - ($new_width - $thumb_width) / 2, 
                       0 - ($new_height - $thumb_height) / 2, 
                       0, 0,
                       $new_width, $new_height,
                       $width, $height);

    imagesavealpha($thumb, true); //preseve alpha
    $newFileName = $folder. "/" .basename($file);
    imagepng($thumb, $newFileName, 9);
}
?>


<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="myform" name="myform" method="POST"  enctype="multipart/form-data" class="my-5">
		<div class="bg-white p-5 shadow-sm">
			<h2 class="text-center mb-4">Insert Podcast Information</h2>

            <div class="row">
                <div class="col-12 col-lg-6">
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

                    <div class="form-group mt-3 required">
			            <label for="description">Image:</label>
			            <input type="file" name="myfile">
		            </div>
                </div>

                <div class="col-12 col-lg-6">
                    
                   <div class="form-group mt-3">
                    <label for="platform" class="mb-1">Platform:</label>

                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="applePod" name="applePod" value="1" <?php if(isset($applePod) && $applePod = "1") {echo "checked";} ?>>
                            <label class="form-check-label" for="applePod">Apple Podcasts</label>
                        </div>
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="spotify" name="spotify" value="1" <?php if(isset($spotify) && $spotify = "1") {echo "checked";} ?>>
                            <label class="form-check-label" for="spotify">Spotify</label>
                        </div>
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="googlePod" name="googlePod" value="1" <?php if(isset($googlePod) && $googlePod = "1") {echo "checked";} ?>>
                            <label class="form-check-label" for="googlePod">Google Podcasts</label>
                        </div>
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="youtube" name="youtube" value="1" <?php if(isset($youtube) && $youtube = "1") {echo "checked";} ?>>
                            <label class="form-check-label" for="youtube">Youtube</label>
                        </div>
                   </div>

                    <div class="form-group mt-4 required">
                        <label for="podUrl" class="mb-1">Podcast Url:</label>
                        <input type="text" class="form-control" id="podUrl" name="podUrl" value="<?php if($podUrl){echo $podUrl;} ?>">
                        <?php if($podUrlMsg){echo $msgPreError.$podUrlMsg. $msgPost;}?>
                    </div>
                    <div class="form-group mt-3 required">
                        <label for="description" class="mb-1 required">Description:</label>
                        <textarea class="form-control" rows="5" name="description" value="<?php if($description){echo $description;} ?>"></textarea>
                        <?php if($descriptionMsg){echo $msgPreError.$descriptionMsg. $msgPost;}?>
                    </div>

                    <div class="form-group mt-3">
                        <input type="submit" name="submit" class="btn btn-info px-3 w-100 py-2" value="Submit">
                    </div>
                </div>
            </div>

            <?php
				if($msgSuccess) {
					echo $msgPreSuccess . $msgSuccess . $msgPost;
				}

                if($valMessage){
                    if($valid == 1){
                        echo "<div class=\"alert alert-info\"><p><i class=\"fas fa-check-circle fa-3x fa-pull-left\"></i>$valMessage</i>
                        <img src=\"../thumbs200/\" width=\"50\" height=\"50\" class=\"thumbnail\"> </p></div>";
                    }else {
                        echo "<div class=\"alert alert-danger\"><p><i class=\"fas fa-exclamation-triangle fa-2x fa-pull-left\"></i>
                        $valMessage </p></div>";
                    }
                }
			?>
			
		</div>
</form>

<?php include ("../includes/footer.php"); ?>