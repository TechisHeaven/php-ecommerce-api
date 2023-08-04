<?php
//controllers user
require("controllers/userController.php");
include("db/connection.php");

// Handle user-related routes
$request_method = $_SERVER["REQUEST_METHOD"];


switch ($request_method) {
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
                    //create user
                case 'create':
                    $userController = new  UserController($mysqli);
                    $user = $userController->createUser();
                    header('Content-Type: application/json');
                    echo json_encode($user);
                    break;
                // get user by email.
                case 'login':
                    $userController = new  UserController($mysqli);
                    $user = $userController->getUserByEmail();
                    header('Content-Type: application/json');
                    echo json_encode($user);
                    break;
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
        } else {
            // No action specified
            header('Content-Type: application/json');
            $error = array('message'=>"Bad request",'type'=> "blank", 'status'=> 400);
            echo json_encode($error);
            header("HTTP/1.0 400 Bad Request");
        }
        break;

    case 'POST':
        // Handle POST requests
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'create':
                    // Create a new user
                    $userController = new UserController();
                    // You need to implement the createUser() function in UserController
                    $result = $userController->createUser($_POST['username'], $_POST['email']);
                    header('Content-Type: application/json');
                    echo json_encode($result);
                    break;

                // Add other actions for updating, deleting users, etc.

                default:
                    // Invalid action
                    header("HTTP/1.0 400 Bad Request");
                    break;
            }
        } else {
            // No action specified
            header("HTTP/1.0 400 Bad Request");
        }
        break;

    // Add cases for other HTTP methods (PUT, DELETE, etc.) and corresponding actions

    default:
        // Invalid request method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
