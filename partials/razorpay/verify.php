<?php

ob_end_clean();
require('../fpdf/fpdf.php');

require('config.php');
require('razorpay-php/Razorpay.php');
include '../_dbconnect.php';

session_start();

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

function generateReceipt($sid, $stud_name, $course, $semester, $amt, $date, $trans_id, $output){

    // Instantiate and use the FPDF class
    $pdf = new FPDF();

    //Add a new page
    $pdf->AddPage();

    $pdf->Image('../logo.png',10,10,30,30);

    // Set the font for the text
    $pdf->SetFont('Arial', 'BU', 24);
    $pdf->Cell(0,30,'E-College',0,1,'C');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0,30,'Fees Receipt',0,1,'L');

    $pdf->Cell(0,10,'',0,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(75,10,'Student ID',1,0,'L');
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(0,10,"$sid",1,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(75,10,'Name',1,0,'L');
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(0,10,"$stud_name",1,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(75,10,'Course',1,0,'L');
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(0,10,"$course",1,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(75,10,'Semester',1,0,'L');
    $pdf->SetFont('Arial','', 16);
    $pdf->Cell(0,10,"$semester",1,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0,10,'',1,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(75,10,'Amount',1,0,'L');
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(0,10,"Rs.$amt",1,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(75,10,'Date',1,0,'L');
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(0,10,"$date",1,1,'L');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(75,10,'Transaction ID',1,0,'L');
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(0,10,"$trans_id",1,1,'L');

    $pdf->Cell(0,50,'',0,1,'L');


    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0,10,'Seal of Institute',0,0,'L');
    $pdf->Cell(0,10,'Authorized Signature',0,1,'R');


    // return the generated output
    // $pdf->Output();
    $content = $pdf->Output("../../fee_receipt/$output",'F');

}

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true)
{   
    $html = "<p>Your payment was successful</p>
             <p>Payment ID: {$attributes['razorpay_payment_id']}</p>
             <p>Razorpay Order ID: {$attributes['razorpay_order_id']}</p>";

    $f_id = $_POST['f_id'];
    $stud_id = $_SESSION['stud_id'];

    // Get required student data
    $queryStud = "SELECT * from `student` NATURAL JOIN `course` where `stud_id` = '$stud_id'";
    $resultStud = mysqli_query($conn, $queryStud);
    $student = mysqli_fetch_assoc($resultStud);

    $stud_name = $student['f_name']. " ". $student['m_name']. " ". $student['l_name'];
    $course = $student['course_id']. " - ". $student['course_name'];
    $semester = $student['semester'];

    $amt = $_POST['amount'];
    $date = date("Y-m-d");
    $trans_id = $attributes['razorpay_payment_id'];
    $order_id = $attributes['razorpay_order_id'];

    $output = $f_id. "_". $stud_id. ".pdf";

    generateReceipt($stud_id, $stud_name, $course, $semester, $amt, $date, $trans_id, $output);
    
    $queryUpdateFees = "UPDATE `fees_stud` SET `status` = 'in progress', `trans_status` = 'success', `trans_date` = '$date', `trans_id` = '$trans_id', `order_id` = '$order_id', `receipt` = '$output' WHERE `f_id` = $f_id and `stud_id` = '$stud_id'";
    $resultUpdateFees = mysqli_query($conn, $queryUpdateFees);
    
    echo "<script>window.location.href='/e-college/student/payFees?success=true';</script>";
}
else
{
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
    echo "<script>window.location.href='/e-college/student/payFees?success=false';</script>";
}

// echo $html;

?>