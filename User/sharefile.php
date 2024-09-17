<?php
include('header.php');
include('../dbconnection.php');
session_start();

if(isset($_SESSION['user_id']))
{
    $file_id    =   $_REQUEST['fid'];
    $qry        =   "SELECT user_details.user_id, user_details.user_first_name FROM user_details LEFT JOIN shared_files ON shared_files.shared_user_id = user_details.user_id AND shared_files.file_id = ".$file_id." WHERE user_details.user_id != '".$_SESSION['user_id']."' AND shared_files.shared_user_id IS NULL";
    $run        =   mysqli_query($conn,$qry);
    $row        =   mysqli_num_rows($run);
} else {
    header('location: ../login.php');
}

?>
    <div class="dashboard">
        <div class="admintitle">
            <h3 style=" float: right;"><a href="userdashboard.php" class="nav_menu">Dashboard</a>
            <a href="../logout.php" class="nav_menu" >Logout</a></h3>                
        </div>
        <h2 align="center">Share File </h2>       
            <form method="post" action="userdashboard.php" enctype="multipart/form-data">
                <div class="container" style="width: 500px; margin-top: 110px;">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="user_select" style="font-size: 25px;">Select User</label>
                            <select name="user_select" id="user_select" class="form-control">
                                <?php      
                                if($row == 0){
                                    ?>
                                    <option value="">No users to share</option>
                                
                                <?php } while($data = mysqli_fetch_assoc($run)): ?>
                                    <option value="<?php echo $data['user_id']; ?>"><?php echo $data['user_first_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary form-control" name="shareFile">Share</button>
                            </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="file_id" value="<?php echo $file_id; ?>">
                </div>
            </form>
        </div>
</body>
</html>