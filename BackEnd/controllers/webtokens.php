<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

const SECRET_KEY = 'random-key123';
const ISSUER = 'nexus';
const AUDIENCE = 'idk';

// Validating credentials and generating token
function generateToken($user) {
    $payload = [
        'iss' => ISSUER,
        'aud' => AUDIENCE,
        'iat' => time(), // Issued at
        'exp' => time() + 3600, // Expiration time
        'sub' => $user['id']
    ];

    $jwt = JWT::encode($payload, SECRET_KEY);
    return $jwt;
}

function validateToken($jwt) {
    try {
        $decoded = JWT::decode($jwt, SECRET_KEY, array('HS256'));
        return $decoded;
    } catch (Exception $e) {
        // Handle token validation error, e.g., log or return false
        return false;
    }
}