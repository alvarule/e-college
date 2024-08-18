<?php
session_start();
include '../partials/_nav.php';

// check whether user is staff
if($_SESSION['end_user']!="staff"){
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
    $queryGetLect = "SELECT * from `lecture` where `l_id` = '$l_id'";
    $resultGetLect = mysqli_query($conn, $queryGetLect);

    if(mysqli_num_rows($resultGetLect)==0){    
        echo "
        <script type='text/javascript'>
        window.location.href = '/e-college/staff/createLecture';
        </script>
        ";
        exit;
    }

    $lecture = mysqli_fetch_assoc($resultGetLect);

    $apiKey = "5_jS6uQ6QBOQAWms3EQr1Q";
    $apiSecret = "8YUR3qt2EORXfsizv25TYH3TAQYzEJGBQBY7";

    $meeting_id = $lecture['meeting_id'];
    $meeting_pass = $lecture['meeting_pass'];
    $role = 1;

    $signature = generate_signature($apiKey, $apiSecret, $meeting_id, $role);

    $params = "name=$fullname&mn=$meeting_id&email=&pwd=$meeting_pass&role=$role&lang=en-US&signature=$signature&china=0&apiKey=$apiKey&redirect=/e-college/staff/createLecture";

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Conduct Lecture</title>
    </head>
    <body class="body-own">
        <div class="panel-transparent">

            <iframe src="/e-college/partials/zoom/join/meeting?<?php echo $params; ?>" allow="camera;microphone" frameborder="0" width="100%" height="550px"></iframe>

        </div>

        <div class="panel-transparent">
            <div class="row">
                <p class="col-sm-12 content-bold">Manage Attendance</p>
                <form action='' onsubmit='return updateAttendance(1);' class="col-sm-6">
                    <input type='submit' class='btn-full-width' value='Start'>
                </form>
                <form action='' onsubmit='return updateAttendance(0);' class="col-sm-6">
                    <input type='submit' class='btn-full-width' value='Stop'>
                </form>
            </div>
        </div>

        <script>
            function updateAttendance($val)
            {
                var url = "/e-college/partials/_updateAttendance?l_id=<?php echo $l_id; ?>&status="+$val;

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
