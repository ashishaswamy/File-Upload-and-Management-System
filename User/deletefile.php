<?php

include('../dbconnection.php');
    $id         =   $_REQUEST['fid'];
    $data           =   "SELECT * FROM uploaded_files WHERE  file_id = '$id'";
    $selected_data  = mysqli_query($conn,$data);
    $result         = mysqli_fetch_assoc($selected_data);
  
    $qry        =   "DELETE FROM uploaded_files WHERE  file_id = '$id'";
    $run        =   mysqli_query($conn,$qry);

    $sql        =   "DELETE FROM shared_files WHERE  file_id = '$id'";
    $run_sql    =  mysqli_query($conn,$sql); 
    if(file_exists("../images/".$result['file_path'].'/'. $result['file_name'])){
        unlink("../images/".$result['file_path'].'/'. $result['file_name']);
    }

    if($run == true) {
?>
        <div id="customAlert" class="custom-alert">
            <div class="custom-alert-content">
                <p>Data Deleted Successfully</p>
                <button onclick="closeAlert()" class="alert-btn">OK</button>
            </div>
        </div>
    
        <script>
            document.getElementById('customAlert').style.display = 'block';
    
            function closeAlert() {
                document.getElementById('customAlert').style.display = 'none';
                window.location.href = 'userdashboard.php';
            }
        </script>
        <style>
            .custom-alert {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
            }
    
            .custom-alert-content {
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
            }
    
            .alert-btn {
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
<?php
    }
?>