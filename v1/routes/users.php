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

                // Add other actions for retrieving specific users, etc.

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