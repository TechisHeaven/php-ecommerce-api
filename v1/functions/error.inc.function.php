<?php 


function CustomError($errorCode, $errorMessage){
    header('Content-Type: application/json');
    $error = array('error'=> "true", 'error_message'=>$errorMessage, 'status'=>$errorCode);
    return $error;
}


function customRealEscapeString($mysqli, $string) {
    return $mysqli->real_escape_string($string);
}




?>