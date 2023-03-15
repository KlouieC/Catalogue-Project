<?php

$con = mysqli_connect("localhost", "kcombes2","QQlTEV2w5ZNZtGV","kcombes2_dmit2025");

// Check connection 
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


foreach ($_GET as $key => $value) { 
    $_GET[$key] = mysqli_real_escape_string($con, $value); 
}


define("BASE_URL", "https://kcombes2.dmitstudent.ca/dmit2025/catalogue/");
  

  // Your App Name
define("APP_NAME", "Podcast Collection");
?>