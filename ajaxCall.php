<?php

include("vendor/autoload.php");
include 'config.php';

$action = $_GET['action'];

switch ($action) {
    case "updatePaperStatus":
        if(isset($_POST['paperID']) && isset($_POST['status']) && isset($_POST['changeStatus'])){
            $sqlString = "UPDATE `uploadpapers` SET `last_status`='".$_POST['changeStatus']."',`status`='".$_POST['status']."',`date_updated`='".date('y-m-d h:i:s')."' WHERE fileID = '".$_POST['paperID']."' ";
            $query = mysqli_query($conn, $sqlString);
        }

        break;
}


?>