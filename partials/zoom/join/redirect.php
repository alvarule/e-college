<?php

session_start();
if($_SESSION['end_user']=="student"){
?>
<script>self.parent.location.replace('http://localhost/e-college/student/todaysLecture');</script>
<?php
}
elseif($_SESSION['end_user']=="staff"){
?>
<script>self.parent.location.replace('http://localhost/e-college/staff/createLecture');</script>
<?php
}

?>