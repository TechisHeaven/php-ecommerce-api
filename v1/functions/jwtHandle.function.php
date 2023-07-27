<?php 
require("../vendor/autoload.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function createJwtToken($userId, $expirationInSeconds) {
    $secretKey = 'test';
    $algorithm = 'HS256';
    // Create the payload for the token
    $payload = [
        "data" => $userId, // The subject of the token (e.g., user ID)
        "iat" => time(), // The time at which the token was issued (current timestamp)
        "exp" => time() + $expirationInSeconds // The expiration time of the token (in seconds from now)
    ];

    // Create the token using the Firebase JWT library
    $token = JWT::encode($payload, $secretKey, $algorithm);

    return $token; // Return the JWT token
}





function verifyJwtToken($token) {
    $secretKey = 'test';
    $algorithm = 'HS256';
    try {
        // Verify and decode the token using the Firebase JWT library
        $decodedToken = JWT::decode($token, new Key($secretKey, $algorithm));
        
        // If the token is valid, return the decoded data (payload)
        return $decodedToken;
    } catch (Exception $e) {
        // If the token is invalid or has expired, return false
        error_log('Error decoding token: ' . $e->getMessage());
        return false;
    }
}





?>