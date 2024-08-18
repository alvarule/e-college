<?php
session_start();
include '../partials/_nav.php';

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

// func to generate zoom meeting joining signature
function generate_signature ( $api_key, $api_sercet, $meeting_number, $role){

    $time = time() * 1000 - 30000; //time in milliseconds (or close enough)

    $data = base64_encode($api_key . $meeting_number . $time . $role);

    $hash = hash_hmac('sha256', $data, $api_sercet, true);

    $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);

    //return signature, url safe base64 encoded
    return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
}

if(isset($_GET['l_id'])){
    $l_id = $_GET['l_id'];

    // check if the lecture exists
    $queryGetLect = "SELECT * from `lecture` where `l_id` = '$l_id'";
    $resultGetLect = mysqli_query($conn, $queryGetLect);

    if(mysqli_num_rows($resultGetLect)==0){    
        echo "
        <script type='text/javascript'>
        window.location.href = '/e-college/student/todaysLecture';
        </script>
        ";
        exit;
    }

    $lecture = mysqli_fetch_assoc($resultGetLect);

    $apiKey = "ZB-HFStBSPil9TwIrNfnmQ";
    $apiSecret = "d2vzlK30diS1Zft6uxoetondRqzxC3pKUlis";

    $meeting_id = $lecture['meeting_id'];
    $meeting_pass = $lecture['meeting_pass'];
    $role = 0;

    $queryStud = "SELECT * from `student` where `stud_id` = $studentID";
    $resultStud = mysqli_query($conn, $queryStud);
    $student = mysqli_fetch_assoc($resultStud);
    $name = $student['roll_no']. " - ". $student['f_name']. " ". $student['l_name'];

    $signature = generate_signature($apiKey, $apiSecret, $meeting_id, $role);

    $params = "name=$name&mn=$meeting_id&email=&pwd=$meeting_pass&role=$role&lang=en-US&signature=$signature&china=0&apiKey=$apiKey&redirect=/e-college/student/todaysLecture";

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <title>Join Lecture</title>      
        
    </head>
    <body class="body-own">
        <div class="panel-transparent">

            <iframe src="/e-college/partials/zoom/join/meeting?<?php echo $params; ?>" allow="camera;microphone" frameborder="0" width="100%" height="550px"></iframe>

        </div>

        <div class="panel-transparent" id="attendanceDiv"></div>

        <script type="text/javascript">
            // func to fire request to _checkAttendance.php without refreshing the page
            function update() {
                $.get("/e-college/partials/_checkAttendance.php?l_id=<?php echo $l_id; ?>", function(data) {
                    document.getElementById("attendanceDiv").innerHTML = data; // will display the button to mark attendance
                    window.setTimeout(update, 5000); // demo
                });
            }
            
            // will fire a request to _checkAttendance.php every 1 min to check if attendance is activated or not
            update();

            // func to mark attendance without refreshing the page
            function markAttendance()
            {
                var url = "/e-college/partials/_markAttendance?l_id=<?php echo $l_id; ?>";

                // get the URL
                http = new XMLHttpRequest(); 
                http.open("GET", url, true);
                http.send(null);

                // prevent form from submitting
                return false;
            }

        </script>
        
    </body>
    </html>

<?php  
}
?>
