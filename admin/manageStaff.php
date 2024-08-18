<?php
session_start();

// check whether user is admin
if($_SESSION['end_user']!="admin"){
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
    <title>Staff Management</title>


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- Importing Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Marcellus+SC&family=Mitr:wght@300&family=Outfit:wght@300;500&display=swap" rel="stylesheet">      
    
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
<body  class="body-own">

<?php
        if(isset($_GET['success'])){
            if($_GET['success']){
    ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Staff details has been updated successfully..
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
    <?php
            }
            else {
    ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Something went wrong! </strong>Student details not updated successfully..
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
        }
    }
    ?>


    <h1 class="page-heading">Manage Staff</h1>
    <div class="panel-transparent">
        
        <table class="table table-responsive table-hover content-bold" id="myTable">
            <thead>
                <tr>
                    <th class="content-bold">Staff ID</th>
                    <th class="content-bold">Name</th>
                    <th class="content-bold">View/Edit</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $sql = "SELECT * FROM `staff`";
                $result= mysqli_query($conn,$sql);
                while($row = mysqli_fetch_assoc($result)){
            ?>
                <tr>
                    <td class='content-light' scope='row'><?php echo $row['staff_id']; ?></td>
                    <td class='content-light'><?php echo $row['f_name']. " ". $row['m_name']. " ". $row['l_name']; ?></td>
                    <td class='content-light'><i class='fa fa-eye' onclick="window.location.href='/e-college/admin/editStaff?s_id=<?php echo $row['staff_id'];?>';"></i></td>
                </tr>
            <?php
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
    