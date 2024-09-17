<?php
include('header.php');
include('../dbconnection.php');
session_start();
if(!isset($_SESSION['user_id'])) {
    header('location: ../login.php');
}
?>
    <div class="dashboard">
        <div class="admintitle">
            <h3 style=" float: right;"><a href="userdashboard.php" class="nav_menu">Dashboard</a>
            <a href="../logout.php" class="nav_menu" >Logout</a></h3>                
        </div>
        <h2 align="center"> Upload File </h2> 
            <form method="post" action="userdashboard.php" enctype="multipart/form-data">
                <div class="container" style="width: 500px; margin-top: 110px;">
                   
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="userImg" style="font-size: 25px; alig">Choose File</label>
                            <input type="file" name="userImg" id="userImg" class="form-control" style="font-size: 15px !important;" required>
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary form-control" name="addFile">Submit</button>
                            </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                </div>
            </form>
        </div>
</body>
</html>
