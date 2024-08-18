<?php
include '_dbconnect.php';

session_start();

// check whether user is staff
if($_SESSION['end_user']=="staff" or $_SESSION['end_user']=="student"){
    
    if(isset($_GET['id']) and isset($_GET['file'])){
    
        $notesID = $_GET['id'];
        $fileType = $_GET['file'];
        $queryNotes = "SELECT * from `notes` WHERE `id` = $notesID";
        $resultNotes = mysqli_query($conn, $queryNotes);
        if(mysqli_num_rows($resultNotes)==0){
            echo "<h1>Unavailable Resource</h1>";
        }
        else{
            $notes = mysqli_fetch_assoc($resultNotes);
            
            if($fileType=="pdf"){
                $resultFile = $notes['notes_doc']; 
            }
            elseif($fileType=="mp3"){
                $resultFile = $notes['audio_note']; 
            }
            else{
                echo "<h1>Unavailable Resource</h1>";
                exit;
            }

            $file = "../notes/$resultFile";       
            
            if(!file_exists($file)){ // file does not exist
                die('file not found');
            } 
            else {
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=". basename($file));
                header("Content-Type: ". mime_content_type($file));
                header("Content-Transfer-Encoding: binary");
                
                // read the file from disk
                readfile($file);
                echo "File Downloaded";
            }
            echo "
            <script type='text/javascript'>
            window.location.href = 'provideNotes';
            </script>
            ";        
        }
    }
}
else{
    header("Location: /e-college/index");
    exit;
}
?>