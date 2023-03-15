<?php include ("includes/header.php"); ?>

<header class="banner">
        <section>
            <div class="inner-container flex">
                <h2>The story behind the world's  <span>&mdash; interesting sounds!</span></h2>
                <p>From the edges of the spectrum. This project will be a catalogue of the different podcast that I listened to over the past few years.This podcast has various genres/topics, from meditation podcasts to society and culture.</p>

                <a href="about.php" class="btn1"><span>Learn More</span></a>
                
            </div>
       </section>
       <br><br>
</header>

<?php 

// session_start();
// -----------------------
if(isset($_GET['sortby'])) {
    $sortby = $_GET['sortby'];
} else {
    $sortby = "";
}

if(isset($_GET['episodes'])) {
    $episodes = $_GET['episodes'];
} else {
    $episodes = "";
}

if(isset($_GET['year'])) {
    $year = $_GET['year'];
} else {
    $year = "";
}

$queryAppend = array();
if($sortby != "") {
    array_push($queryAppend, "$sortby");
}

$queryFilter = "";
foreach($queryAppend as $k => $v) {
    if($k == 0) { // if it's the first item, do this
        $queryFilter .= " ORDER BY " . $v;
    } 
}

//Pagination


$displayby = "";
$displayvalue = "";
$limstring = "";
$min = "";
$max = "";
$getcount = "";

if(isset($_GET['displayby'])) {
    $displayby = $_GET['displayby'];
}

if(isset($_GET['displayvalue'])) {
    $displayvalue = $_GET['displayvalue'];
}

if(isset($_GET['min'])) {
    $min = $_GET['min'];
}

if(isset($_GET['max'])) {
    $max = $_GET['max'];
}

if($displayby && $displayvalue) {
    $getcount = mysqli_query($con,"SELECT COUNT(*) FROM podcasts_collection WHERE $displayby LIKE '%$displayvalue%'");
} else if ($displayby && $min && $max) {
    if($displayby === "episodes") {
        $getcount = mysqli_query($con,"SELECT COUNT(*) FROM podcasts_collection WHERE $displayby BETWEEN '$min' AND '$max'");
    }
} else {
    $getcount = mysqli_query($con,"SELECT COUNT(*) FROM podcasts_collection");
}


$postnum = mysqli_result($getcount,0);
$limit = 6;
if($postnum > $limit){
$tagend = round($postnum % $limit,0);
$splits = round(($postnum
-
$tagend)/$limit,0);
if($tagend == 0){
	$num_pages = $splits;
}else{
	$num_pages = $splits + 1;
}
if(isset($_GET['pg'])){
	$pg = $_GET['pg'];
}else{
	$pg = 1;
}
$startpos = ($pg*$limit)-$limit;
$limstring = "LIMIT $startpos,$limit";
}else{
$limstring = "LIMIT 0,$limit";
}
// MySQLi upgrade
function mysqli_result($res, $row, $field=0) {
	$res->data_seek($row);
	$datarow = $res->fetch_array();
	return $datarow[$field];

}

// echo "<h3>$limstring</h3>";


if($displayby && $displayvalue){ // FILTERED
	$sql = "SELECT * FROM podcasts_collection WHERE $displayby LIKE '%$displayvalue%' $queryFilter $limstring";
} else { // UNFILTERED
    $sql = "SELECT * FROM podcasts_collection $queryFilter $limstring";
}


if($displayby === "episodes"){
    $min = $_GET['min'];
    $max = $_GET['max'];
    $sql = "SELECT * FROM podcasts_collection WHERE episodes BETWEEN '$min' AND '$max' $queryFilter $limstring";
}

$result = mysqli_query($con, $sql) or die(mysqli_error($con));

?>

<?php

    if($displayby === "episodes") {
        echo "<h1>Podcasts Collection - <b>$displayby: $min - $max Episodes</b></h1>";
    } elseif ($displayby === "") {
        echo "<h1>Podcasts Collection</h1>";
    } else {
        if ($displayby === "year") {
            if ($displayvalue == "2015%") {
                $displayvalue = "2015s";
            } else if ($displayvalue == "2016%") {
                $displayvalue = "2016s";
            } else if ($displayvalue == "2017%") {
                $displayvalue = "2017s";
            } else if ($displayvalue == "2018%") {
                $displayvalue = "2018s";
            } else if ($displayvalue == "2020%") {
                $displayvalue = "2020s";
            } else if ($displayvalue == "2021%") {
                $displayvalue = "2021s";
            }else if ($displayvalue == "2022%") {
                $displayvalue = "2022s";
            }
            echo "<h1>Podcasts - <b>$displayby: $displayvalue</b></h1>";
        } else {
            echo "<h1>Podcasts - <b>$displayby: $displayvalue</b></h1>";
        }
    } 

?>

<div class="row">
    <div class="col-9">
        <div class="mt-3 mb-4">
            <?php

                if (strlen($_SERVER['QUERY_STRING']) > 25) {
                    if($postnum > $limit){
                        $n = $pg + 1;
                        $p = $pg - 1;

                        $thisroot = $_SERVER['REQUEST_URI']; 
                        
                        $thisroot = remove_url_query($thisroot, "pg");


                        if($pg > 1){
                            echo "<ul class=\"pagination\"><li class=\"page-item\"><a class=\"page-link\" href=\"$thisroot&pg=$p\">Previous</a></li>";
                        }
                        for($i=1; $i<=$num_pages; $i++){
                        if($i!= $pg){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"$thisroot&pg=$i\">$i</a></li>";
                        }else{
                            echo "<ul class=\"pagination\"><li class=\"page-item\"><a class=\"page-link bg-primary text-white\">$i</a></li>";
                        }
                        }
                        if($pg < $num_pages){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"$thisroot&pg=$n\">Next</a></li></ul>";
                        }
                            echo "";
                    }
                } else { 
                    if($postnum > $limit){
                        $n = $pg + 1;
                        $p = $pg - 1;

                        $thisroot = $_SERVER['PHP_SELF']; 
                        if($pg > 1){
                            echo "<ul class=\"pagination\"><li class=\"page-item\"><a class=\"page-link\" href=\"$thisroot?pg=$p\">Previous</a></li>";
                        }
                        for($i=1; $i<=$num_pages; $i++){
                        if($i!= $pg){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"$thisroot?pg=$i\">$i</a></li>";
                        }else{
                            echo "<ul class=\"pagination\"><li class=\"page-item\"><a class=\"page-link bg-primary text-white\">$i</a></li>";
                        }
                        }
                        if($pg < $num_pages){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"$thisroot?pg=$n\">Next</a></li></ul>";
                        }
                            echo "";
                    }
                }
                    
                
            ?>
        </div>    

        <div class="grid">
            <?php while ($row = mysqli_fetch_array($result)): ?>

            <div class="card bg-white">
            <img src="<?php echo "thumbs200/" . $row['filename']; ?>">
                <div class="card-body p-0">
                    <div class="p-3 bg-light border-bottom">
                        <h5 class="card-title"><?php echo htmlentities(utf8_encode($row['podTitle']), 0, "UTF-8"); ?></h5>
                        <h6 class="card-subtitle text-muted"><?php echo $row['hostName']; ?></h6>
                    </div>
                    <div class="p-3">
                        <p class="card-text">
                            <b>Running Since: </b><?php echo $row['year']; ?><br>
                            <b>Episodes: </b><?php echo $row['episodes']; ?><br>
                            <b>Genre: </b><?php echo $row['genre']; ?><br>
                            <b>Language: </b><?php echo $row['language']; ?><br>
                    </p>
                    </div>

                    <a class="btn1" href="output.php?id=<?php echo $row['id'];?>"><span>Details</span></a>
                </div>
            </div>

            <?php endwhile ?>
        </div>
    </div>

    <div class="col-3">
        <div class="bg-light p-3">

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                <label><h4>Sort By</h4></label>
                <select name="sortby" onchange="this.form.submit()" class="form-select">
                    <option value="">-- Select --</option>
                    <option value="Episodes" <?php if($sortby == "episodes"){echo "selected=\"selected\"";}?>>Episode: Lowest to Highest</option>
                    <option value="Episodes DESC" <?php if($sortby == "episodes DESC"){echo "selected=\"selected\"";}?>>Episode: Highest to Lowest</option>
                    <option value="Year" <?php if($sortby == "year"){echo "selected=\"selected\"";}?>>Year: Older First</option>
                    <option value="Year DESC" <?php if($sortby == "year DESC"){echo "selected=\"selected\"";}?>>Year: Most Recent First</option>
                </select>
            </form>

            <div class="my-3 border-top border-dark py-3">
                <h4>Genres</h4>
                <a href="index.php?displayby=Genre&displayvalue=Comedy&sortby=<?php echo $sortby?>">Comedy</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=Culture&sortby=<?php echo $sortby?>">Culture</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=Health&sortby=<?php echo $sortby?>">Health</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=Music&sortby=<?php echo $sortby?>">Music</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=Philosophy&sortby=<?php echo $sortby?>">Philosophy</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=Relationship&sortby=<?php echo $sortby?>">Relationship</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=Society&sortby=<?php echo $sortby?>">Society</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=True Crime&sortby=<?php echo $sortby?>">True Crime</a><span> |</span>
                <a href="index.php?displayby=Genre&displayvalue=Self-help&sortby=<?php echo $sortby?>">Self-help</a>
            </div>


            <div class="my-3 border-top border-dark py-3">
                <h4>Languages</h4>
                <a href="index.php?displayby=Language&displayvalue=English&sortby=<?php echo $sortby?>">English</a><span> |</span>
                <a href="index.php?displayby=Language&displayvalue=Filipino&sortby=<?php echo $sortby?>">Filipino</a><span> |</span>
                <a href="index.php?displayby=Language&displayvalue=Korean&sortby=<?php echo $sortby?>">Korean</a>
    
            </div>

            <div class="my-3 border-top border-dark py-3">
            <h4>Running Since</h4>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2015%&sortby=<?php echo $sortby?>">2015s</a><span> |</span>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2016%&sortby=<?php echo $sortby?>">2016s</a><span> |</span>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2017%&sortby=<?php echo $sortby?>">2017s</a><span> |</span>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2018%&sortby=<?php echo $sortby?>">2018s</a><span> |</span>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2019%&sortby=<?php echo $sortby?>">2019s</a><span> |</span>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2020%&sortby=<?php echo $sortby?>">2020s</a><span> |</span>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2021%&sortby=<?php echo $sortby?>">2021s</a><span> |</span>
                <a id="yearnum" href="index.php?displayby=Year&displayvalue=2022%&sortby=<?php echo $sortby?>">2022s</a> 
            </div>


            <div class="my-3 border-top border-dark py-3">
            <h4>Episodes</h4>
                <div><a href="index.php?displayby=episodes&min=20&max=180&sortby=<?php echo $sortby?>">  20 - 180 Episodes</a></div>
                <div><a href="index.php?displayby=episodes&min=180&max=280&sortby=<?php echo $sortby?>"> 180 - 280 Episodes</a></div>
                <div><a href="index.php?displayby=episodes&min=280&max=480&sortby=<?php echo $sortby?>"> 280 - 480 Episodes</a></div>
                <!-- <div><a href="index.php?displayby=episodes&min=480&max=780&sortby=<?php echo $sortby?>"> 480 - 680 Episodes</a></div> -->
            </div>

        </div>
    </div>
</div>

<?php
function remove_url_query($url, $key) {
    $url = preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', "$1", $url);
    $url = rtrim($url, '?');
    $url = rtrim($url, '&');
    return $url;
} 
?>



<?php include ("includes/footer.php"); ?>