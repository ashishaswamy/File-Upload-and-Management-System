<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Generate CSRF token if it's not already created
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Create a 32-byte random token
}

if(isset($_SESSION['user_id']))
{
   // header('location:admin/admindash.php');
}
?>
<html lang="en_US">
    <head>
        <meta charset ="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css"  type="text/css">
        <title>User Login </title>

    <style>
        @media (min-width: 1200px) {
            .container {
                width: 500px;
            }
        }
    </style>
        
    </head>
    <body>
        <h1 align="center">User Login</h1><br>
        <form action="login.php" method="post">
        <div class="container">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="usr_email">Email ID</label>
                    <input type="email" class="form-control" id="usr_email" name="usr_email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="usr_pw">Password</label>
                    <input type="password" class="form-control" id="usr_pw" name="usr_pw" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <a href="index.php" type="submit" class="btn btn-primary">Sign Up</a>
                </div>
                <div class="form-group col-md-6" align="right">
                    <button type="submit" class="btn btn-primary" name="login">Sign In</button>
                </div>               
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        </div>
        </form>
    </body>
</html>

<?php

include('dbconnection.php');

if(isset($_POST['login'])){
    
    $useremail = mysqli_real_escape_string($conn,$_POST['usr_email']);
    $password = mysqli_real_escape_string($conn,$_POST['usr_pw']);

    $password = md5($password);
    
    $qry = "SELECT * FROM user_details WHERE user_email = '$useremail' AND user_password = '$password'";
    
    $run = mysqli_query($conn,$qry);
    
    $row = mysqli_num_rows($run);
    
    if($row>=1)
    {
        $data = mysqli_fetch_assoc($run);
        $id = $data['user_id'];
        $_SESSION['user_id']=$id;
        header('location:user/userdashboard.php');   
    }
    else
    {
        ?>
         <script>
            alert('Username Or Password Dont match');
            window.open('login.php','_self')
        </script>
        <?php
    }
}

?>