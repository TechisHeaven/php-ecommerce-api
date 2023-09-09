<?php
//controllers Billboards
require("controllers/BillboardController.php");
include("db/connection.php");

// Handle user-related routes
$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method){
    case 'POST':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'create':
                    $BillboardController = new  BillboardController($mysqli);
                    $billboard = $BillboardController->createBillboard();
                    header('Content-Type: application/json');
                    echo json_encode($billboard);
                    break;
                default:
                    // Invalid action
                    // No action specified
                    header('Content-Type: application/json');
                    $error = array('message'=>"Bad Request",'type'=> "action",  'status'=> 400);
                    echo json_encode($error);
                    header("HTTP/1.0 400 Bad Request");
                    break;
                }
            }
        break;
    case 'PUT':
        if(isset($_GET['action'])){
            switch($_GET['action']){
                case 'update':
                    $BillboardController = new BillboardController($mysqli);
                    $billboard = $BillboardController->updateBillboard();
                    header('Content-Type: application/json');
                    echo json_encode($billboard);
                    break;
            }
        }
        break;
    case 'GET':
        if(isset($_GET['action'])){
            switch($_GET['action']){
                case 'all':
                    $BillboardController = new  BillboardController($mysqli);
                    $billboard = $BillboardController->getAllBillboard();
                    header('Content-Type: application/json');
                    echo json_encode($billboard);
                    break;
                case 'getById':
                    $BillboardController = new  BillboardController($mysqli);
                    $billboard = $BillboardController->getBillboardById();
                    header('Content-Type: application/json');
                    echo json_encode($billboard);
                    break;
                    
            }
        }
   default:
        // Invalid request method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

?>

