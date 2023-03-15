<?php include ("includes/header.php"); ?>

        <div id="carousel" class="carousel slide banner" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel" data-slide-to="1"></li>
                    <li data-target="#carousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="img/Podcast-hero.webp" alt="First slide">

                        <div class="carousel-caption d-none d-md-block">
                            <p class="text-dark"> Podcasting, or simply a podcast, is an audio program, much like talk radio. But with this, you can subscribe to a specific show using your smartphone and listen to it whenever you like.</p>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img class="d-block w-100" src="img/banner.webp" alt="Second slide">

                        <div class="carousel-caption d-none d-md-block">
                            <h2 class="text-dark">POD &middot; CAST</h2>
                            <p style="font-weight: 700;" class="text-white"> Today is the Digital Era, and podcasting is another way to integrate modern technology. It immersed me in an enlightening conversation over the airwaves</p>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img class="d-block w-100" src="img/slide3.webp" alt="Third slide">

                        <div class="carousel-caption d-none d-md-block">
                            <h2 class="text-white" style="font-size: 3rem; letter-spacing: 4px;">POD &middot; CAST</h2>
                            <p class="text-white"> In simple terms, podcasting is like listening to an audio story, but instead of hearing stories, it usually consists of conversations between two or more people.
                            </p>
                        </div>
                    </div>
                </div>

                <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
        </div>


        <div class="text-dark py-5 text-center mt-5">
            <h1 class="display-5 fw-bold">Podcast Recommendation Gallery</h1>
        </div>

        <?php

        $sql = "SELECT * FROM podcasts_collection";
        $result = mysqli_query($con, $sql) or die ("ERROR:" . mysqli_error($on));

        echo 
        "<div style=\"display: flex; justify-content: center; flex-wrap:wrap;\">";
        while($row = mysqli_fetch_array($result)){ ?>

            <div class="m-2 thumb bg-white text-center">
                
                <div><a href="output.php?id=<?php echo $row['id'];?>"> <img src="<?php echo "thumbs200/" . $row['filename']; ?>"></a></div>
                <div class="podTitle"><?php echo $row['podTitle']?></div>
                
            </div>

        <?php }

        echo "</div>";
        ?>



<?php include ("includes/footer.php"); ?>