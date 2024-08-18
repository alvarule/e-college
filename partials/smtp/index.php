<?php
include 'smtp/PHPMailerAutoload.php';

function smtp_mailer($to,$subject, $msg){
	$mail = new PHPMailer(); 
	$mail->SMTPDebug  = 3;
	$mail->IsSMTP(); 
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'tls'; 
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->Username = "info.ecollege.2022@gmail.com";
	$mail->Password = "btaeimrperxgtqug";
	$mail->SetFrom("info.ecollege.2022@gmail.com");
	$mail->Subject = $subject;
	$mail->Body =$msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	if(!$mail->Send()){
		echo $mail->ErrorInfo;
	}else{
		return 'Sent';
	}
}

if($_SERVER['REQUEST_METHOD']=="POST"){

	$toEmail = $_POST['email'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	smtp_mailer($toEmail, $subject, $message);
	
}
?>