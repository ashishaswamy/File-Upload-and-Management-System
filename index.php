<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Generate CSRF token if it's not already created
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Create a 32-byte random token
}
?>

<html>
    <head>
        <meta charset ="UTF-8">
        <!-- <link rel="stylesheet" href="css/bootstrap.min.css"  type="text/css"> -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <title> File Management System</title>
        
        
    </head>
    <body>
              
            <h1 align="center"> Welcome To File Management System</h1>          
            <h2 align="center">Register Here</h2>   


            <?php

if(isset($_POST['submit'])){
    
    include('dbconnection.php');
    if (isset($_POST['csrf_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

            $first_name     = htmlspecialchars($_POST['usr_fname'], ENT_QUOTES, 'UTF-8');
            $last_name      = htmlspecialchars($_POST['usr_lname'], ENT_QUOTES, 'UTF-8');
            $email_addr     = htmlspecialchars($_POST['usr_email'], ENT_QUOTES, 'UTF-8');
            $phone_num      = htmlspecialchars($_POST['usr_phn'], ENT_QUOTES, 'UTF-8');
            $password       = htmlspecialchars($_POST['usr_pw'], ENT_QUOTES, 'UTF-8');
            $cnfrm_pw       = htmlspecialchars($_POST['usr_cpw'], ENT_QUOTES, 'UTF-8');

            if ($password === $cnfrm_pw) {

                $password = md5($password);

                $sql = "SELECT user_id FROM user_details where user_email='$email_addr'";

                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result)<1){

                    $qry = "INSERT INTO user_details(user_first_name, user_last_name, user_email, user_phone_number, user_password) VALUES  ('$first_name',' $last_name','$email_addr','$phone_num','$password')";

                    $run = mysqli_query($conn,$qry);

                    $last_id = mysqli_insert_id($conn);
                    $username = preg_replace('/[^A-Za-z0-9_\-]/', '', $email_addr);
                    $directory = 'images/' . $username . '_' . $last_id;

                    if (!file_exists($directory)) {
                        if (mkdir($directory, 0755, true)) {
                            
                            $dir_name = $username . '_' . $last_id;
                            $qry1 = "UPDATE user_details SET user_directory = '$dir_name' WHERE user_id = $last_id";
   
                            $run1 = mysqli_query($conn,$qry1);
                            echo "<p style='color:green; text-align:center; font-size: large;'>User Created Successfully</p>";
                        } else {
                            echo "<p style='color:red; text-align:center; font-size: large;'>Failed to create folder for user.</p>";
                        }
                    } else {
                        echo "<p style='color:red;text-align:center; font-size: large;'>Folder already exists.</p>";
                    }
                    

                } else {
                    echo "<p style='color:red;text-align:center; font-size: large;'>User with same email id already exist.</p>";
                }
            } else {
                echo "<p style='color:red;text-align:center; font-size: large;'>Passwords do not match. Please try again.</p>";
            }          

        } else {
             echo "<p style='color:red;text-align:center; font-size: large;'>Invalid CSRF token</p>";
        }
    } else {

         echo "<p style='color:red;text-align:center; font-size: large;'>No CSRF token found.</p>";
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
            <form method="post" action="index.php">
                <div class="container">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usr_fname">First Name</label>
                            <input type="text" class="form-control" id="usr_fname" name="usr_fname" placeholder="Enter First Name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usr_lname">Last Name</label>
                            <input type="text" class="form-control" id="usr_lname" name="usr_lname" placeholder="Enter Last Name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usr_email">Email address</label>
                            <input type="email" class="form-control" id="usr_email" name="usr_email" placeholder="Enter email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usr_phn">Mobile Number</label>
                            <input type="number" class="form-control" id="usr_phn" name="usr_phn" placeholder="58*******"  min="8" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usr_pw">Password</label>
                            <input type="password" class="form-control" id="usr_pw" name="usr_pw" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usr_cpw">Confirm Password</label>
                            <input type="password" class="form-control" id="usr_cpw" name="usr_cpw" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <a type="submit" href="login.php" class="btn btn-primary">Sign In</a>
                        </div>
                        <div class="form-group col-md-6" align="right">
                            <button type="submit" class="btn btn-primary" name="submit">Register</button>
                        </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                </div>
            </form>
       
    </body>   
</html>
