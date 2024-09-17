<?php

include('../dbconnection.php');
session_start();

if(isset($_SESSION['user_id']))
{
    $id     =   $_REQUEST['fid'];
    $qry    =   "SELECT file_name,file_path FROM uploaded_files WHERE  file_id = '$id'";
    $run    =   mysqli_query($conn,$qry);
    $data1  =   mysqli_fetch_assoc($run);
   
    
} else {
    header('location: ../login.php');
}
include('header.php');
?>
    <div class="dashboard">
        <div class="admintitle">
            <h3 style="float:right; margin-right:30px;"><a href="userdashboard.php" class="nav_menu">Dashboard</a>
            <a href="../logout.php" class="nav_menu">Logout</a></h3>               
        </div>
        <h2 align="center">Move File </h2>       
            <form method="post" action="userdashboard.php">
                <div class="container" style="width: 500px; margin-top: 110px;">
                   
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="new_name">Directory Name</label>
                            <input type="text" name="new_dir" id="new_dir" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary form-control" name="moveFile">Update</button>
                            </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="file_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="file_name" value="<?php echo $data1['file_name']; ?>">
                    <input type="hidden" name="exist_file_path" value="<?php echo $data1['file_path']; ?>">
                </div>
            </form>
        </div>
</body>
</html>
