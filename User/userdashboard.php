<?php
include('../dbconnection.php');
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Create a 32-byte random token
}

if(isset($_POST['rename'])) {
    if (isset($_POST['csrf_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $user_id    =   $_SESSION['user_id'];
            $file_id    =   $_POST['file_id'];
            $file_path  =   $_POST['exist_file_path'];
            $extension  =   $_POST['extension'];
        //get directory details
            $sql        =   "SELECT user_directory FROM user_details where user_id='$user_id'";
            $result     =   mysqli_query($conn,$sql);
            $data       =   mysqli_fetch_assoc($result);

            $oldName    =   htmlspecialchars($_POST['old_name'], ENT_QUOTES, 'UTF-8');
            $newName    =   htmlspecialchars($_POST['new_name'], ENT_QUOTES, 'UTF-8');
            $newName    =   preg_replace('/[^A-Za-z0-9_\.-]/', '', $newName).'_'.time();
            

            $old_name   =   "../images/".$file_path.'/'. $oldName.'.'.$extension;
            $new_name   =   "../images/".$file_path.'/'. $newName.'.'.$extension;

            if (file_exists($old_name)) {         
                    if (rename($old_name, $new_name)) {
                        $file_name  =   $newName.'.'.$extension;                   
                        $qry        =   "UPDATE uploaded_files SET file_name = '$file_name' WHERE file_id = $file_id"; 
                        $run        =   mysqli_query($conn,$qry);
                        $message    =   "File Renamed succefully !!";
                        $class      =   "success";
                    } else {
                        $message    =   "Error: Renaming file.";
                        $class      =   "error";
                    }
            } else {
                $message    =   "Error: File not exist.";
                $class      =   "error";
            }
    
        } else {
            $message    =   "Error: Invalid CSRF token";
            $class      =   "error";
        }
    } else {
        $message    =   "Error: No CSRF token found.";
        $class      =   "error";
    }   
} else if(isset($_POST['moveFile'])) {
    if (isset($_POST['csrf_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $user_id    =   $_SESSION['user_id'];
            $file_id    =   $_POST['file_id'];
            $file_name  =   htmlspecialchars( $_POST['file_name'], ENT_QUOTES, 'UTF-8');
            $file_path  =   $_POST['exist_file_path'];
        //get directory details
            $sql        =   "SELECT user_directory FROM user_details where user_id='$user_id'";
            $result     =   mysqli_query($conn,$sql);
            $data       =   mysqli_fetch_assoc($result);

            $old_name   =   "../images/".$file_path.'/'.$file_name;
            $new_name   =   "../images/".$data['user_directory']."/". htmlspecialchars($_POST['new_dir'], ENT_QUOTES, 'UTF-8')."/". htmlspecialchars( $file_name, ENT_QUOTES, 'UTF-8');

            $directory  =   "../images/".$data['user_directory']."/". htmlspecialchars($_POST['new_dir'], ENT_QUOTES, 'UTF-8');
            if (file_exists($old_name)) {
                if(!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }            
                if (rename($old_name, $new_name)) {
                    $path_name  =   $data['user_directory'].'/'.htmlspecialchars($_POST['new_dir'], ENT_QUOTES, 'UTF-8');                   
                    $qry        =   "UPDATE uploaded_files SET file_path = '$path_name' WHERE file_id = $file_id"; 
                    $run        =   mysqli_query($conn,$qry);
                    $message    =   "File Moved succefully !!";
                    $class      =   "success";

                } else {
                    $message    =   "Error: Moving file.</p>";
                    $class      =   "error";
                }

            } else {
                $message    =   "Error: File not exist.</p>";
                $class      =   "error";
            }
        } else {
             $message   =   "Error: Invalid CSRF token</p>";
             $class     =   "error";
        }
    } else {
         $message   =   "Error: No CSRF token found.</p>";
         $class     =   "error";
    }
} else if(isset($_POST['shareFile'])) {
    if (isset($_POST['csrf_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $user_id        =   $_SESSION['user_id'];
            $file_id        =   $_POST['file_id'];
            $shared_user_id =   $_POST['user_select'];
            if($shared_user_id !='' && !empty($shared_user_id)) {
                $qry        =   "INSERT INTO shared_files(file_id, user_id, shared_user_id) VALUES  ('$file_id',' $user_id','$shared_user_id')";
                $run        =   mysqli_query($conn,$qry);
                $message    =   "File Shared succefully !!";
                $class      =   "success"; 
            } else {
                $message    =   "Error: Select a user first.";
                $class      =   "error";
            }
        } else {
            $message    =   "Error: Invalid CSRF token.";
            $class      =   "error";
        }
    } else {
         $message   =   "Error: No CSRF token found.";
         $class     =   "error";
    }
   
}else if(isset($_POST['addFile'])){
    if (isset($_POST['csrf_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $user_id    =   $_SESSION['user_id'];

            if (isset($_FILES['userImg'])) {
                $file               =   $_FILES['userImg'];
                $imagename          =   $file['name'];
                $tempname           =   $file['tmp_name']; 
                $fileType           =   mime_content_type($tempname);
                $validImageTypes    =   ['image/jpeg', 'image/png'];
                $maxFileSize        =   5 * 1024 * 1024;
                $basename           =   pathinfo($imagename, PATHINFO_FILENAME);
                $image_extension    =   pathinfo($imagename, PATHINFO_EXTENSION);
                $new_imagename      =   $basename.'_'.time() . '.' . $image_extension;

                if (in_array($fileType, $validImageTypes)) {
                    if ($file['size'] <= $maxFileSize) {

                        $sql        =   "SELECT user_directory FROM user_details where user_id='$user_id'";
                        $result     =   mysqli_query($conn,$sql);
                        $data       =   mysqli_fetch_assoc($result);
                        move_uploaded_file($tempname,"../images/".$data['user_directory']."/$new_imagename");
                        $file_path  =   $data['user_directory'];
                        $qry        =   "INSERT INTO uploaded_files (user_id, file_name, file_path) VALUES ('$user_id','$new_imagename','$file_path')";
                        $run        =   mysqli_query($conn,$qry);
                        $message    =   "File uploaded successfully!!";
                        $class      =   "success";       
                    } else {
                       $message =   "Error: File size exceeds 5MB";
                       $class   =   "error";   
                    }
                } else {
                    $message    =   "Error: Only JPEG and PNGimage files are allowed.";
                    $class      =   "error"; 
                }
            } else {
                $message    =   "Error: No file uploaded.";
                $class      =   "error"; 
            }     
        } else {
            $message    =   "Error: Invalid CSRF token";
            $class      =   "error"; 
        }
    } else {

        $message    =   "Error:  No CSRF token found.";
        $class      =   "error";
    }
}


if(isset($_SESSION['user_id']))
{
    $uid = $_SESSION['user_id'];
    $qry = "SELECT * FROM uploaded_files WHERE user_id = '$uid' ";
    $run = mysqli_query($conn,$qry);   
    $row = mysqli_num_rows($run);
} else {
    header('location: ../login.php');
}

include('header.php');
?>
    <div class="dashboard">
        <div class="admintitle">    
            <h3><a href="../logout.php" class="nav_menu" style=" float: right;">Logout</a></h3>    
        </div>
        <h2 align="center"> Dashboard </h2>
        <h5 align="right" style="padding-right: 30px;"><a href="addnewfile.php"  style="padding-right: 10px;" class="btn btn-primary">Upload New File</a> 
        <a href="sharelist.php" class="btn btn-primary">Shared File Details</a></h5>
            <?php if(isset($message) && !empty($message)) { ?>
                <label class="<?= ($class ? $class : '') ?>"><?php echo $message; ?> </label>
            <?php } ?>
            
        <table align="center" width="80%" border="1" style:"margin-top:10px;">
            <tr style="background-color:#000; color:#fff; " align="center">
                <th>No.</th>
                <th>File Name</th>
                <th>Action</th>
            </tr>
                <?php      
                if($row == 0){
                    echo "<tr><td colspan='5'>No Records Found</td></tr>";
                } else {
                    $count=0;
                    while($data=mysqli_fetch_assoc($run)) {
                    $count++;
                ?>
            <tr align="center">
                <td><?php echo $count;?></td>
                <td><img src="../images/<?php echo $data['file_path'] ?>/<?php echo $data['file_name'] ?>" style="max-width:100px;"/> </td>
                <td><a href="renamefile.php?fid=<?php echo $data['file_id']; ?>" class="btn">Rename</a>
                <a href="movefile.php?fid=<?php echo $data['file_id']; ?>" class="btn">Move</a>
                <a href="sharefile.php?fid=<?php echo $data['file_id']; ?>" class="btn">Share</a>
                <a href="deletefile.php?fid=<?php echo $data['file_id']; ?>" class="btn">Delete</a></td>
            </tr>
                <?php
                    }
                }
                ?>
        </table>  
    </div>
</body>
</html>
