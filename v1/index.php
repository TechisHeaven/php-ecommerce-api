<?php
require_once 'db/connection.php'; // Replace with the actual filename

// Handle API versioning and routing
$request_uri = $_SERVER["REQUEST_URI"];
$api_version = 'v1';


$api_version_position = strpos($request_uri, "/$api_version/");

// Check if the URI contains the API version and route accordingly
if ($api_version_position !== false) {

    $request_uri = substr($request_uri, strlen("/$api_version/"));

    // Split the remaining URI into parts to identify the route and action
    $uri_parts = explode('/', $request_uri);
    // Check the first part of the URI (the route)
    switch ($uri_parts[3]) {
        case 'users':
            // Handle user-related routes
            require_once "routes/users.php";
            break;

        case 'posts':
            // Handle post-related routes
            require_once "routes/posts.php";
            break;

        case 'billboards':
            // Handle post-related routes
            require_once "routes/billboards.php";
            break;

        case 'comments':
            // Handle comment-related routes
            require_once "routes/comments.php";
            break;

        default:
            // Invalid route
            header("HTTP/1.0 404 Not Found");
            break;
    }
} else {
    // Invalid API version or route
    header("HTTP/1.0 404 Not Found");
}
$mysqli->close();
?>



