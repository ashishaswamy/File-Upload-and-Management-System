<?php
include('header.php');
include('../dbconnection.php');
session_start();

if(isset($_SESSION['user_id']))
{
    $qry_shared_with_me =   " SELECT uploaded_files.file_id, uploaded_files.file_name,uploaded_files.file_path, user_details.user_first_name AS shared_by  FROM shared_files INNER JOIN uploaded_files ON shared_files.file_id = uploaded_files.file_id INNER JOIN user_details ON shared_files.user_id = user_details.user_id WHERE shared_files.shared_user_id = '".$_SESSION['user_id']."'";
    $run                =   mysqli_query($conn,$qry_shared_with_me);
    $row                =   mysqli_num_rows($run);

   $qry_shared_by_me    =   " SELECT uploaded_files.file_id, uploaded_files.file_name,uploaded_files.file_path, user_details.user_first_name AS shared_with FROM shared_files INNER JOIN uploaded_files ON shared_files.file_id = uploaded_files.file_id INNER JOIN user_details ON shared_files.shared_user_id = user_details.user_id WHERE shared_files.user_id = '".$_SESSION['user_id']."'";
   $run1                =   mysqli_query($conn,$qry_shared_by_me);
   $row1                =   mysqli_num_rows($run1);

} else {
    header('location: ../login.php');
}

?>
    <div class="dashboard">
        <div class="admintitle">
            <h3 style=" float: right;"><a href="userdashboard.php" class="nav_menu">Dashboard</a>
            <a href="../logout.php" class="nav_menu" >Logout</a></h3>                
        </div>
        <h2 align="center">Files Shared With You</h2>       
        <table align="center" width="80%" border="1" style:"margin-top:10px;">
            <tr style="background-color:#000; color:#fff; " align="center">
                <th>No.</th>
                <th>File</th>
                <th>Shared By</th>
            </tr>
                <?php      
                if($row == 0){
                    echo "<tr><td colspan='5'>No Records Found</td></tr>";
                }
                else{
                    $count=0;
                    while($data=mysqli_fetch_assoc($run)){
                    $count++;
                ?>
            <tr align="center">
                <td><?php echo $count;?></td>
                <td><img src="../images/<?php echo $data['file_path'] ?>/<?php echo $data['file_name'] ; ?>" style="max-width:100px;"/> </td>
                <td><?php echo $data['shared_by'] ?></a>
                </td>
            </tr>
                <?php
                    }
                }
                ?>
        </table>

        <h2 align="center">Files Shared By You</h2>       
        <table align="center" width="80%" border="1" style:"margin-top:10px;">
            <tr style="background-color:#000; color:#fff; " align="center">
                <th>No.</th>
                <th>File</th>
                <th>Shared To</th>
            </tr>
                <?php      
                if($row1<1){
                    echo "<tr><td colspan='5'>No Records Found</td></tr>";
                }
                else{
                    $count1=0;
                    while($data1=mysqli_fetch_assoc($run1)){
                    $count1++;
                ?>
            <tr align="center">
                <td><?php echo $count1;?></td>
                <td><img src="../images/<?php echo $data1['file_path']?>/<?php echo $data1['file_name'] ?>" style="max-width:100px;"/> </td>
                <td><?php echo $data1['shared_with'] ?></td>
            </tr>
                <?php
                    }
                }
                ?>
        </table>  
    </div>
</body>
</html>
