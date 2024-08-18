<?php
session_start();

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<?php

// getting subjects list for the student
$querySubjects = "SELECT * FROM `subject` NATURAL JOIN `student` WHERE `stud_id` = $studentID";
$resultSubjects = mysqli_query($conn, $querySubjects);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/e-college/style.css">
    <title>Notes</title>

</head>
<body class="body-own">
    <p class="page-heading">My Notes</p>
    <div class="accordion accordion-flush" id="accordionExample">

    <?php
        if(mysqli_num_rows($resultSubjects)==0){
            echo "<div class='panel-transparent'>
            <p class='content-light'>No Subjects found</p>
            </div>";
        }
        else{
            // looping through the subjects
            while($subject=mysqli_fetch_assoc($resultSubjects)){
    ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="sub">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub<?php echo $subject['sub_code']; ?>" aria-expanded="false" aria-controls="collapseOne">
                            <p class="content-bold"><?php echo $subject['sub_code']. " - ". $subject['sub_abbr']; ?></p>
                        </button>
                    </h2>
                    <div id="sub<?php echo $subject['sub_code']; ?>" class="accordion-collapse collapse" aria-labelledby="sub">
                        <div class="accordion-body">

                        <?php
                            $sub_code = $subject['sub_code'];
                            $queryNotes = "SELECT * from `notes` NATURAL JOIN `subject` WHERE `sub_code` = $sub_code";
                            $resultNotes = mysqli_query($conn, $queryNotes);
                            if(mysqli_num_rows($resultNotes)==0){
                                echo "<div class='panel-transparent'>
                                <p class='content-light'>No Notes found</p>
                                </div>";                                
                            }
                            else{
                        ?>
                                <table class="table align-middle table-hover">
                        <?php
                                // looping through notes for the current subject
                                while($notes = mysqli_fetch_assoc($resultNotes)){
                        ?>
                                    <tr>
                                        <td class="content-bold"><?php echo $notes['title']; ?></td>
                        
                                        <td class="content-light"><i class="fa fa-eye" data-toggle="modal" data-target="#<?php echo $notes['id']; ?>"></td>
                        
                                        <td class="content-bold"><i class="fa fa-download" onclick="window.open('/e-college/partials/_downloadNotes?id=<?php echo $notes['id']; ?>&file=pdf', '_blank');"></i></td>
                                        
                                        <td class="content-bold"><i class="fa fa-headphones" onclick="window.location.href='/e-college/partials/_downloadNotes?id=<?php echo $notes['id']; ?>&file=mp3';"></i></td>
                                    </tr>
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
                    </div>
                </div>  
    <?php
            }
        }
    ?>
        
    </div>
</body>
</html>