<?php 

    ///////////////META CONFIGURATION/////////////////
    session_start();
    error_reporting(E_ALL);

    ///////////////CONSTANT DEFINED//////////////////
    define("BASEURL","http://localhost/examination//");

    ///////////////DATABASE CONFIGURATIONS//////////////////
    $server = "localhost";
    $username = "root";
    $password = "";
    $db = "examination";

    $conn = mysqli_connect($server,$username,$password,$db);

    if($conn){
      //Connection successfully established
    }
    else
        die("connection to this database failed due to " .mysqli_connect_error()); //connection not establised
  
      /**
       * @param int $number
       * @return string
       */

      function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
?>