<?php
include("mysql_connect.php");// here we include the connection script; since this file(header.php) is included at the top of every page we make, the connection will then also be included. Also, config options like BASE_URL are also available to us.

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
   
    <!--  This CONSTANT is defined in your mysql_connect.php file. -->
    <title><?php echo APP_NAME; ?></title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">



    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    
    <!-- Google Fonts --> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@100;400&display=swap" rel="stylesheet">

    <!-- Google Icons: https://material.io/tools/icons/ -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <!-- Your Custom styles for this project -->
    <!--  Note how we can use BASE_URL constant to resolve all links no matter where the file resides. -->
    <link href="<?php echo BASE_URL ?>css/styles.css" rel="stylesheet">

</head>

  <body>

    
    <nav class="navbar navbar-expand-md navbar-light mb-4 fixed-top" style="background-color: #90B4CE;">
      <a class="navbar-brand" href="<?php echo BASE_URL ?>index.php">
        <span class="material-icons">podcasts</span> Podcast Collection
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">

          <li class="nav-item">
            <a href="<?php echo BASE_URL ?>about.php" class="nav-link">About</a>
          </li>
         
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown01">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>admin/insert.php">
                            <span class="material-icons pt-1">playlist_add</span>&nbsp;&nbsp;Insert(Secured)</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>admin/edit.php">
                            <span class="material-icons pt-1">edit_note</span>&nbsp;&nbsp;Edit(Secured)</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>admin/logout.php">
                        <span class="material-icons pt-1">logout</span>&nbsp;&nbsp;Logout</a></li>
                </ul>
          </li>

          <li class="nav-item active">
            <a href="https://open.spotify.com/search/podcast" target="_blank" class="btn2 bg-primary text-white nav-link"><span>Listen to the Podcast</span></a>
          </li>

         
        </ul>
        
        <!-- Search Bar:  -->

        <?php 

            $searchterm = "";

            if(isset($_POST['searchterm']) && strlen($_POST['searchterm']) > 2):
                $searchterm = $_POST['searchterm'];
                
                $sql = "SELECT * FROM podcasts_collection WHERE
                    podTitle LIKE '%$searchterm%'
                    OR hostName LIKE '%$searchterm%'
                    OR description LIKE '%$searchterm%'
                ";
                
                $result = mysqli_query($con, $sql);
                ?>  

            <?php endif; ?>


          <form class="form-inline mt-2 mt-md-0" action="<?php echo BASE_URL ?>search.php" method="post">
        
          <input class="form-control mr-sm-2" type="search" id="podcast" placeholder="Search" aria-label="Search" name="searchterm" value="<?php echo $searchterm; ?>">
          <button class="btn btn-outline-dark my-2 my-sm-0" name="mysubmit" type="submit">Search</button>
        </form>
      </div>
    </nav>

    <main role="main" class="container">



    

      

    




