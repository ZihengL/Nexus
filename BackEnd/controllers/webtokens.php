<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

const SECRET_KEY = 'random-key123';
const ISSUER = 'nexus';
const AUDIENCE = 'idk';
const ALGORITHM = 'HS256';

function generateToken($user) {
    $payload = [
        'iss' => ISSUER,
        'aud' => AUDIENCE,
        'iat' => time(), // Issued at
        'exp' => time() + 3600, // Expiration time
        'sub' => $user['id']
    ];

    $jwt = JWT::encode($payload, SECRET_KEY, ALGORITHM);
    return $jwt;
}

function validateToken($jwt) {
    try {
        $decoded = JWT::decode($jwt, new Key(SECRET_KEY, ALGORITHM));
        return $decoded;
    } catch (Exception $e) {
        return false;
    }
}