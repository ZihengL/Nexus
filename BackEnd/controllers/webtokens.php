<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

// Validating credentials and generating token
function generateToken($user) {
    $exampleSecretKey = '12345';

    $payload = [
        'iss' => 'your-issuer', // Issuer
        'aud' => 'your-audience', // Audience
        'iat' => time(), // Issued at
        'exp' => time() + 3600, // Expiration time
        'sub' => $user['id'] // Subject (user ID)
    ];

    $jwt = JWT::encode($payload, $exampleSecretKey);
    return $jwt;
}

function validateToken($jwt) {
    $decoded = JWT::decode($jwt, 'your_secret_key', array('HS256'));
}