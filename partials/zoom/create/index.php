<?php
session_start();
include('config.php');
include('api.php');
include '../../_dbconnect.php';

// check whether user is staff
// if($_SESSION['end_user']!="staff"){
//     header("Location: /e-college/index");
//     exit;
// }

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// password generation
	$combination = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); 
	$combinationLen = strlen($combination) - 1; 
	for ($i = 0; $i < 6; $i++) {
		$n = rand(0, $combinationLen);
		$pass[] = $combination[$n];
	}
	$password = implode($pass);
	

	$topic = $_POST['subject']. " Lecture";
	$start_date = $_POST['date']. " ". $_POST['end_time'];

	$arr['topic']="$topic";
	$arr['start_date']="$start_date";
	$arr['password']="$password";
	$arr['type']='2';
	$result_meet=createMeeting($arr);

	if(isset($result_meet->id)){

		// Inserting Data into DB
		$sub_code = $_POST['subject'];
		$meeting_id = $result_meet->id;
		$meeting_pass = $result_meet->password;
		$date = $_POST['date'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		
		$query = "INSERT INTO `lecture` (`sub_code`, `meeting_id`, `meeting_pass`, `date`, `start_time`, `end_time`) VALUES ('$sub_code', '$meeting_id', '$meeting_pass', '$date', '$start_time', '$end_time')";
		$result = mysqli_query($conn, $query);

		if($result){
		header("Location: /e-college/staff/createLecture?success=1");
		}
		else{
			?>
			<script>alert("Lecture Not Created")</script>
			<?php
			header("Location: /e-college/staff/createLecture?success=0");
		}
	}
	else{
		?>
		<script>alert("Lecture Not Created")</script>
		<?php
		header("Location: /e-college/staff/createLecture?success=0");
	}
}

?>