<?php include ("includes/header.php"); ?>


<?php 

$id = "";
$idPrevious = "";
$idNext = "";
$nextprevButton = "";


$id = $_GET['id'];
if(!is_numeric($id)) {
    header("Location:index.php");
}

$result = mysqli_query($con, "SELECT * FROM `podcasts_collection` WHERE id = $id" ); 

//NEXT - PREVIOUS BUTTONS

$next = mysqli_query($con,"SELECT id FROM podcasts_collection WHERE id = (select min(id) from podcasts_collection where id > '$id') LIMIT 1");
while ($row = mysqli_fetch_array($next)){
    $idNext = $row['id'];
}

$prev =  mysqli_query($con,"SELECT id FROM podcasts_collection WHERE id = (select max(id) from podcasts_collection where id < '$id') LIMIT 1");
while ($row = mysqli_fetch_array($prev)){
    $idPrevious = $row['id'];
}

if($idPrevious){
    $nextprevButton .= "<a href=\"output.php?id=$idPrevious\" class=\"btn btn-info btn-xs\">Previous</a>&nbsp;&nbsp;";
}else{
    $nextprevButton .= "<a href=\"\" class=\"btn btn-default btn-xs\" disabled>Previous</a>";
}
if($idNext){
    $nextprevButton .= "<a href=\"output.php?id=$idNext\" class=\"btn btn-info btn-xs\">Next</a>";
}else{
    $nextprevButton .= "<a href=\"\" class=\"btn btn-default btn-xs\" disabled>Next</a>";
}   

while($row = mysqli_fetch_array($result)) { 
     
    
?>
<div class="output row">
    <div class="col-12 col-lg-8 offset-lg-2 bg-white px-0 py-3 shadow-sm">
        <div class="card">
            <img class="card-img-top" src="display/<?php echo $row['filename']?>" alt="Podcast Image Display">

            <table class="table mx-auto my-auto">
            <tr>
                <td colspan="2" class="py-4 text-center">
                    <h2 class="m-0 py-2"><?php echo $row['podTitle']; ?></h2>
                    <p style="color: #66768C;"><?php echo $row['hostName'];?></p>
                </td>
            </tr>
        
            <tr>
                <th scope="row" class="w-25 py-3 px-3"><b>Running Since</b></th>
                <td class="py-3"><?php echo $row['year']; ?></td>
            </tr>
            <tr>
                <th scope="row" class="w-25 py-3 px-3"><b>Genre</b></th>
                <td class="py-3"><?php echo $row['genre']; ?></td>
            </tr>
            <tr>
                <th scope="row" class="w-25 py-3 px-3"><b>No of Episodes</b></th>
                <td class="py-3"><?php echo $row['episodes']; ?></td>
            </tr>
            <tr>
                <th scope="row" class="w-25 py-3 px-3"><b>Podcast Language</b></th>
                <td class="py-3"><?php echo $row['language']; ?></td>
            </tr>

            <tr>
                <th scope="row" class="w-25 py-3 px-3"><b>Platform</b></th>
                <td class="py-3">
                    <?php 
                        if($row['platform_apple'] == "1") { 
                            echo "<img src=\"apple.svg\"  class=\"image\">"; 
                            echo "\n<p class=\"mb-0\"><i>Apple Podcast</i></p>"; 
                        } 
                        if($row['platform_spotify'] == "1") { 
                            echo "<img src=\"spotify.svg\" class=\"image\">"; 
                            echo "\n<p class=\"mb-0\"><i>Spotify Podcast</i></p>"; 
                        } 
                        if($row['platform_google'] == "1") { 
                            echo "<img src=\"google.svg\" class=\"image\">"; 
                            echo "\n<p class=\"mb-0\"><i>Google Podcast</i></p>"; 
                        }
                        if($row['platform_youtube'] == "1") { 
                            echo "<img src=\"youtube.svg\" class=\"image\">"; 
                            echo "\n<p class=\"mb-0\"><i>Youtube Podcast</i></p>"; 
                        } else {
                            echo "\n<p class=\"mb-0\"><i>No Platform</i></p>"; 
                        }; 
                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row" class="w-25 py-3 px-3"><b>Description</b></th>
                <td class="py-3"><?php echo nl2br($row['description']) ?></td>
            </tr>
            <tr>
                <th scope="row" class="w-25 py-3 px-3"><b>Podcast Url</b></th>
                <td class="py-3">
                    <a class="text-decoration-none" style="color: #3DA9FC;" href="<?php echo $row['podUrl'] ?>" target="_blank">
                        <?php echo $row['podUrl']; ?>
                    </a>
                </td>
            </tr>
        </table>
         </div>

         <br>
            <div class="pl-3"><?php echo $nextprevButton; ?>
            <br></div>
    </div>
</div>

<?php }

?> 



<?php include ("includes/footer.php"); ?>