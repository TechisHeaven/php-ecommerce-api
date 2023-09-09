<?php
//controllers user
require("controllers/userController.php");
include("db/connection.php");

// Handle user-related routes
$request_method = $_SERVER["REQUEST_METHOD"];


switch ($request_method) {
    //post request handle
    case "POST":
        if(isset($_GET["action"])){
            switch ($_GET['action']) {
                     //create user
                     case 'create':
                        $userController = new  UserController($mysqli);
                        $user = $userController->createUser();
                        header('Content-Type: application/json');
                        echo json_encode($user);
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
    //put request handle
    case "PUT":
        if(isset($_GET["action"])){
            switch ($_GET['action']) {
                     //create user
                      // update user.
                case 'update':
                    $userController = new  UserController($mysqli);
                    $user = $userController->updateUser();
                    header('Content-Type: application/json');
                    echo json_encode($user);
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
    case 'GET':
        // Handle GET requests
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'all':
                    // Get all users
                    $userController = new UserController($mysqli);
                    $users = $userController->getAllUsers();
                    header('Content-Type: application/json');
                    echo json_encode($users);
                    break;
               
                // get user by email.
                case 'login':
                    $userController = new  UserController($mysqli);
                    $user = $userController->getUserByEmail();
                    header('Content-Type: application/json');
                    echo json_encode($user);
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
        } else {
            // No action specified
            header('Content-Type: application/json');
            $error = array('message'=>"Bad request",'type'=> "blank", 'status'=> 400);
            echo json_encode($error);
            header("HTTP/1.0 400 Bad Request");
        }
        break;

    default:
        // Invalid request method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
