<?php
session_start();

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- Importing Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Marcellus+SC&family=Mitr:wght@300&family=Outfit:wght@300;500&display=swap" rel="stylesheet">     

    <title>Notice</title>

    <style>
        #myTable_filter label, #myTable_length label{
            margin: 0;
            position: static;
            left: 0px;
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
    <h1 class="page-heading">My Notices</h1>
    <div class="panel-transparent">
        
    <table class="table table-responsive table-hover content-bold" id="myTable">
      <thead>
        <tr>
          <th scope="col" class="content-bold">S.No</th>
          <th scope="col" class="content-bold">Title</th>
          <th scope="col" class="content-bold">View</th>
        </tr>
      </thead>
      <tbody>
        <?php
                  $course = $stud_details['course_id'];
                  $sem = $stud_details['semester'];
                  $sql = "SELECT * FROM `notice` where `course_id` = $course and `sem` = $sem";
                  $result= mysqli_query($conn,$sql);
                  $sno = 0;
                  while($row = mysqli_fetch_assoc($result)){
                    $sno = $sno + 1;
                    echo "<tr>
                    <td class='content-light' scope='row'>". $sno. "</td>
                    <td class='content-light'>". $row['title']. "</td>
                    <td class='content-light'><i class='fa fa-eye' data-toggle='modal' data-target='#". $row['n_id']."'></i></td>
                  </tr> 
                  <div class='container'>

                  <!-- Modal -->
                  <div id='". $row['n_id']."' class='modal fade' role='dialog'>
                      <div class='modal-dialog modal-lg'>

                          <!-- Modal content-->
                          <div class='modal-content'>
                              <div class='modal-header'>
                                  <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                  <h4 class='modal-title'>". $row['title']."</h4>
                              </div>
                              <div class='modal-body'>

                                  <p class='content-light'>". $row['matter']. "</p>";

                                  if($row['doc']!=""){
                                    echo "<embed src='/e-college/notice_doc/". $row['doc']."'
                                          frameborder='0' width='100%' height='400px'>";
                                  }
                                  
                                  echo "
                                  <div class='modal-footer'>
                                      <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                                  </div>
                              </div>

                          </div>
                      </div>
                  </div>
                  </div>
                  ";
                }
              
        ?>


      </tbody>
    </table>
  </div>

  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>

</body>
</html>