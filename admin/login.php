<?php
include("/home/kcombes2/data/info.php");
$msg = "";	
    
if(isset($_POST['submit'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    //echo "$username, $password";

    if(($username == $username_good) && (password_verify($password, $pw_enc))){

        session_start();
        $_SESSION['your-random-session-sjfgetwrcvdjdzzz'] = session_id();
    
        if(isset($_GET['refer'])){
            if($_GET['refer'] == "edit"){
               // echo "refer is edit";
                header("Location:edit.php");
            }else{
                //echo "refer is insert";
                header("Location:insert.php");
            }
        }else{
            header("Location:insert.php");
        }

    }else{

        if($username != "" && $password != ""){
            $msg =  "Invalid Login";
        }else{
            $msg =  "Please enter a Username/Password";
        }

    }
        
}
    
    
include("../includes/header.php");
?>

<form id="myform" name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
		<div class="bg-white p-5 shadow-sm">
        <h2 class="text-center mb-4">Login</h2>

            <div class="form-group">
			    <label for="username">Username:</label>
			    <input type="text" name="username" class="form-control">
		    </div>
            <div class="form-group">
			    <label for="password">Password:</label>
			    <input type="password" name="password" class="form-control">
		    </div>
		    <div class="form-group">
			    <label for="submit">&nbsp;</label>
			    <input type="submit" name="submit" class="btn btn-info w-100" value="Submit">
		    </div>

            <?php
                if($msg){
                     echo "<div class=\"alert alert-info\">$msg</div>"; 
                }
            ?>

        </div>
        
</form>


