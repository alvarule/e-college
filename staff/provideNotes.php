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

// Getting Subjects List for the currently logged in staff
$querySubjects = "SELECT * from `subject` where `staff_id` = $staffID";
$resultSubjects = mysqli_query($conn, $querySubjects);

// Getting previously uploaded notes by the currently logged in staff
$queryNotes = "SELECT * FROM `notes` NATURAL JOIN `subject` WHERE `staff_id` = $staffID";
$resultNotes = mysqli_query($conn, $queryNotes);

?>

<?php

if(isset($_GET['del'])){
    $n_id = $_GET['del'];

    // fetching notes details
    $queryNotes = "SELECT * from `notes` where `id` = $n_id";
    $resultNotes = mysqli_query($conn, $queryNotes);
    $notes = mysqli_fetch_assoc($resultNotes);

    // getting filenames of notes pdf and audio notes for deleting
    $doc = "../notes/". $notes['notes_doc'];
    $audioFile = "../notes/". $notes['audio_note'];
 
    // query to delete notes entry from `notes` table
    $queryDelete = "DELETE FROM `notes` WHERE `id` = $n_id";

    // deleting pdf and audio note files and deleting notes entry from database and simultaneously checking if execution is successful
    if(unlink($doc) and unlink($audioFile) and mysqli_query($conn, $queryDelete)){
        $deleted = true;
    }
    else{
        $deleted = false;
    }
}

?>

<?php
$success = false;
if($_SERVER['REQUEST_METHOD']=="POST"){
    $subject = $_POST['subject'];
    $title = $_POST['title'];

    // getting document file values
    $filename = $_FILES["file"]["name"];
    $tempname = $_FILES["file"]["tmp_name"];
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
    if(!in_array($_FILES['file']['type'], $acceptable_types) || !in_array($ext, $acceptable_ext))
    {
        $showAlert = "File Format Not Supported\nIt should be .pdf";
    }

    else{
        // to get the serial no of current data into the table
        $listQuery = "SELECT * from `notes`";
        $listResult = mysqli_query($conn, $listQuery);
        $sno = mysqli_num_rows($listResult)+1;
        
        $uploadnameForDoc = $subject. "_". $title. "_". $sno. ".". $ext; // upload name for notes doc
        $uploadLocForDoc = "../notes/". $uploadnameForDoc; // upload location for notes doc
        
        $uploadnameForAudioNote = $subject. "_". $title. "_". $sno. ".mp3"; // upload name for audio note file

        // upload notes doc to the server
        $resultMove = move_uploaded_file($tempname, $uploadLocForDoc);
        
        // code to convert notes doc into audio file
        $src = "C:/xampp/htdocs/e-college/notes/$uploadnameForDoc";
        $dest = "C:/xampp/htdocs/e-college/notes/$uploadnameForAudioNote";

        $cmd = "python C:/xampp/htdocs/e-college/partials/_pdftoaudio.py --src $src --dest $dest"; // command to execute python utility for converting pdf into audio file
        $output=null; // store the output of the command after execution
        $retval=null; // store the return code of the command after execution
        exec($cmd, $output, $retval); // execute the command
        
        if($retval == 0){
            // query to insert data into the database
            $queryInsert = "INSERT INTO `notes` (`sub_code`, `title`, `notes_doc`, `audio_note`) VALUES ('$subject', '$title', '$uploadnameForDoc', '$uploadnameForAudioNote')";
            $resultQuery = mysqli_query($conn, $queryInsert);
    
            if($resultMove and $resultQuery){
                $success = true;
            }
            else{
                $showAlert = "Something went wrong! Please try again";
            }
        }
        else{
            $showAlert = "Something went wrong! Please try again";
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
    <title>Provide Notes</title>
</head>

<body class="body-own">

    <?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Notes has been uploaded..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    
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

        if(isset($deleted)){
            if($deleted){
        ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Notes Deleted Successfully!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
        
        <?php
            }
            else{
        ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Something went wrong! </strong>Notes Not Deleted
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
        
        <?php
            }
        }
    ?>


    <h1 class="page-heading">Notes</h1>
    <p class="panel-heading">Upload Notes</p>

    <div class="panel-own">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-grp">
                <label for="" class="form-label-own">Subject</label>
                <select name="subject" class="form-input" required>
                    <option disabled selected value="">--</option>

                    <?php
                        while($subject = mysqli_fetch_assoc($resultSubjects)){
                    ?>
                        <option value="<?php echo $subject['sub_code']; ?>"><?php echo $subject['sub_code']. " - ". $subject['sub_abbr']; ?></option>
                    <?php
                        }
                    ?>
                </select>                
            </div>
            <div class="form-grp">
                <label class="form-label-own">Title</label>
                <input type="text" class="form-input" name="title" placeholder="without spaces" required>

                <script>
                    // will prevent whitespaces from entering into the title field
                    var field = document.querySelector('[name="title"]');
                        field.addEventListener('keypress', function ( event ) {  
                        var key = event.keyCode;
                        if (key === 32) {
                            event.preventDefault();
                        }
                    });
                </script>
                
            </div>
            <div class="form-grp">
                <label class="form-label-own">Upload Notes</label>
                <input type="file" class="form-input-file" name="file" accept=".pdf" required>
            </div>
            <input type="submit" value="Upload" class="btn-full-width">
        </form>
    </div>

    <br>
    <p class="panel-heading">Uploaded Notes</p>
    <div class="panel-transparent">

    <?php
        if(mysqli_num_rows($resultNotes)==0){
    ?>
            <p class="content-light">No Records found</p>
    <?php
        }
        else{
    ?>
            <table class="table table-hover table-responsive">
                <thead>
                    <tr>
                        <th class="content-bold">Subject</th>
                        <th class="content-bold">Title</th>
                        <th class="content-bold">View</th>
                        <th class="content-bold">Download</th>
                        <th class="content-bold">Audio Notes</th>
                        <th class="content-bold">Delete</th>
                    </tr>
                </thead>
    <?php        
            while($notes = mysqli_fetch_assoc($resultNotes)){
    ?>
                <tbody>
                    <tr>
                        <td class="content-light"><?php echo $notes['sub_code']. " - ". $notes['sub_abbr']; ?></td>

                        <td class="content-light"><?php echo $notes['title']; ?></td>
                        
                        <td class="content-light"><i class="fa fa-eye" data-toggle="modal" data-target="#<?php echo $notes['id']; ?>"></td>
                        
                        <td class="content-light"><i class="fa fa-download" onclick="window.open('/e-college/partials/_downloadNotes?id=<?php echo $notes['id']; ?>&file=pdf', '_blank');"></td>
                        
                        <td class="content-light"><i class="fa fa-headphones" onclick="window.location.href='/e-college/partials/_downloadNotes?id=<?php echo $notes['id']; ?>&file=mp3';"></td>
                        
                        <td class="content-light"><i class="fa fa-trash" onclick="window.location.href='provideNotes?del=<?php echo $notes['id']; ?>';"></i></td>
                    </tr>
                </tbody>
                <div class="container">

                    <!-- Modal -->
                    <div id="<?php echo $notes['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><?php echo $notes['sub_code']. " - ". $notes['sub_abbr']. " : ". $notes['title']; ?></h4>
                                </div>
                                <div class="modal-body">

                                    <embed src="/e-college/notes/<?php echo $notes['notes_doc']; ?>"
                                        frameborder="0" width="100%" height="400px">

                                    <audio style="width:100%" controls>
                                        <source src="/e-college/notes/<?php echo $notes['audio_note']; ?>" type="audio/mp3">
                                        Your browser does not support the audio element.
                                    </audio>                                         

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

    <?php          
            }
    ?>
            </table>
    <?php
        }
    ?>
        
    </div>
    
</body>
</html>
