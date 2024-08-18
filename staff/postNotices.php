<?php
session_start();

// check whether user is staff
if($_SESSION['end_user']!="staff"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<?php

// deleting a notice
$deleted = false;
if(isset($_GET['del'])){
    $n_id = $_GET['del'];
    $queryNotice = "SELECT * from `notice` where `n_id` = $n_id";
    $resultNotice = mysqli_query($conn, $queryNotice);
    if(mysqli_num_rows($resultNotice)>0){
        $queryDelNotice = "DELETE FROM `notice` WHERE `n_id` = $n_id";
        $resultDelNotice = mysqli_query($conn, $queryDelNotice);
        if($resultDelNotice){
            $deleted = true;
        }
    }
}

?>

<?php
// Getting Courses List
$queryCourse = "SELECT * from `course`";
$resultCourse = mysqli_query($conn, $queryCourse);
?>

<?php
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $title = $_POST["title"];
        $course = $_POST["course_id"];
        $semester = $_POST["semester"];
        
        if($_POST['matter']!=""){
            $matter = $_POST["matter"]; 

            $sql = "INSERT INTO `notice` (`title`, `matter`, `course_id`, `sem`) VALUES ('$title', '$matter', '$course', '$semester');";

            if(mysqli_query($conn,$sql)){
                $success=true;
            }
            else{
                $showAlert = "Notice was not posted";
            }
        }

        else{
            // getting profile pic values
            $filename = $_FILES["doc"]["name"];
            $tempname = $_FILES["doc"]["tmp_name"];
            $ext = pathinfo($filename, PATHINFO_EXTENSION); // get file extension
            
            //acceptable file types
            $acceptable_types = array(
                'application/pdf'
            );
            
            //acceptable file extensions
            $acceptable_ext = array(
                'pdf'
            );

            // check if file type/ext is valid
            if(!in_array($_FILES['doc']['type'], $acceptable_types) || !in_array($ext, $acceptable_ext))
            {
                $showAlert = "File Format Not Supported\nIt should be .pdf";
            }
            else{
                $queryNotice = "SELECT * from `notice`";
                $resultNotice = mysqli_query($conn, $queryNotice);
                $no_of_notices = mysqli_num_rows($resultNotice)+1;
                
                $uploadname = $no_of_notices. "_". $title. ".". $ext; // upload name for notice doc
                $upload = "../notice_doc/". $uploadname; // upload location for notice 
                
                $sql = "INSERT INTO `notice` (`title`, `doc`, `course_id`, `sem`) VALUES ('$title', '$uploadname', '$course', '$semester');";
                
                $moveSuccess = move_uploaded_file($tempname, $upload);

                if($moveSuccess & mysqli_query($conn,$sql)){
                    $success=true;
                }
                else{
                    $showAlert = "Notice was not posted";
                }
            }
        }

    }    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    
    <style>
        #myTable_filter label, #myTable_length label{
            margin: 0;
            position: static;
            left: 0px;
            /* width: 20%; */
            font-family: 'outfit';
            font-weight: 500;
            font-size: 14pt;
            box-sizing: border-box;
            color: #000;
        }

        #myTable_filter input, #myTable_length select{
            margin: 0;
            position: static;
            right: 0px;
            /* width: 79%; */
            padding: 5px 16px;
            font-family: 'outfit';
            font-size: 14pt;
            font-weight: 300;
            border-radius: 20px;
            outline: none;
            border: 2px solid #D3E0EA;
            background-color: #fff;
            box-sizing: border-box;            
        }

        #myTable_info{
            font-family: 'outfit';
            font-size: 14pt;
            color: #000;
        }
    </style>
        
</head>
<body class="body-own">

<?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Notice was posted succesfully..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
        if($deleted){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Notice deleted successfully..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>

<?php
        if(isset($showAlert)){
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Sorry! </strong><?php echo $showAlert; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>

    <p class="page-heading">Notices</p>
    <p class="panel-heading">Post Notices</p>
    <div class="panel-own">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-grp">
                <label for="Title" class="form-label-own">Title</label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div class="form-grp">
                <label for="Course Code" class="form-label-own">Course</label>
                <select name="course_id" class="form-input" required>
                    <option disabled selected value="">--Choose--</option>
                    <?php
                        while($course = mysqli_fetch_assoc($resultCourse)){
                            $courseID = $course['course_id'];
                            $course_name = $course['course_name'];
                    ?>
                    <option value="<?php echo $courseID; ?>"><?php echo $courseID. " - ". $course_name; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="form-grp">
                <label for="" class="form-label-own">Semester</label>
                <!-- <select name="semester" class="form-input" required multiple> -->
                <select name="semester" class="form-input" required>
                    <option disabled selected value="">--Choose--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                </select>
            </div>

            <div class="form-grp">
                <label class="form-label-own">Notice Format</label>
                <select class="form-input" id="format" onchange="changeFormat()" required>
                    <option value="matter">Written Notice</option>
                    <option value="doc">Document Notice</option>
                </select>
            </div>
            
            <div class="form-grp" id="matter">
                <label class="form-label-own">Matter</label>
                <textarea class="form-input" name="matter" id="matterInp" required></textarea>
            </div>
            <div class="form-grp" id="doc" style="display:none;">
                <label class="form-label-own">Document</label>
                <input type="file" name="doc" class="form-input-file" id="docInp" accept=".pdf">
            </div>

            <script>
                function changeFormat(){
                    var val = document.getElementById("format").value;
                    const matter = document.getElementById("matter");
                    const doc = document.getElementById("doc");
                    const matterInp = document.getElementById("matterInp");
                    const docInp = document.getElementById("docInp");
                    
                    if(val=="matter"){
                        matter.style.display = "block";
                        matterInp.required = true;
                        doc.style.display = "none";
                        docInp.required = false;
                    }
                    else{
                        doc.style.display = "block";
                        docInp.required = true;
                        matter.style.display = "none";
                        matterInp.required = false;
                    }
                }
            </script>

            <input type="submit" value="Post" class="btn-full-width">
        </form>
    </div>

    <br>
    <p class="panel-heading">Notice History</p>
    <div class="panel-transparent">

    <?php
        $sql = "SELECT * FROM `notice` NATURAL JOIN `course`";
        $result= mysqli_query($conn,$sql);
        $sno = 0;
        if(mysqli_num_rows($result)==0){
    ?>  
            <p class="content-light">No Notes found</p>
    <?php
        }
        else{
    ?>
            <table class="table table-responsive table-hover content-bold" id="myTable">
                <thead>
                        <tr>
                            <th scope="col" class="content-bold">S.No</th>
                            <th scope="col" class="content-bold">Title</th>
                            <th scope="col" class="content-bold">Course</th>
                            <th scope="col" class="content-bold">Semester</th>
                            <th scope="col" class="content-bold">View</th>
                            <th scope="col" class="content-bold">Delete</th>
                        </tr>
                </thead>
                <tbody>
                <?php
                    while($row = mysqli_fetch_assoc($result)){
                        $sno = $sno + 1;
                ?>
                        <tr>
                            <td class='content-light' scope='row'><?php echo $sno; ?></td>
                            <td class='content-light'><?php echo $row['title']; ?></td>
                            <td class='content-light'><?php echo $row['course_id']. " - ". $row['course_name']; ?></td>
                            <td class='content-light'><?php echo $row['sem']; ?></td>
                            <td class='content-light'><i class='fa fa-eye' data-toggle='modal' data-target='#<?php echo $row['n_id']; ?>'></i></td>
                            <td class='content-light'><i class='fa fa-trash' onclick="window.location.href='postNotices?del=<?php echo $row['n_id']; ?>';"></i></td>
                        </tr> 

                        <div class='container'>

                        <!-- Modal -->
                        <div id='<?php echo $row['n_id']; ?>' class='modal fade' role='dialog'>
                            <div class='modal-dialog modal-lg'>

                                <!-- Modal content-->
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                        <h4 class='modal-title'><?php echo $row['title']; ?></h4>
                                    </div>
                                    <div class='modal-body'>

                                        <p class='content-light'><?php echo $row['matter']; ?></p>

                                        <?php
                                        if($row['doc']!=""){
                                        echo "<embed src='/e-college/notice_doc/". $row['doc']."'
                                                frameborder='0' width='100%' height='400px'>";
                                        }
                                        ?>
                                        
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
    <?php
                    }
    ?>
                </tbody>
    <?php
            }
    ?>
            </table>
    </div>

    <script>
        $(document).ready(function () {
        $('#myTable').DataTable();
        });
    </script>


</body>
</html>