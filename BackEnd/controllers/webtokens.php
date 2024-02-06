<?php

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class WebTokens
{
    // TODO: Create revocated tokens list. Tokens list stay in memory somewhere.
    // TODO: Create encrypted key

    // Access = authorized AKA access given
    private const ACCESS_KEY = 'temp-access-key';
    private const ACCESS_TIME = 3600;

    // Refresh = authentified AKA not necessarily logged in, but can be given access
    private const REFRESH_KEY = 'temp-refresh-key';
    private const REFRESH_TIME = 604800;

    // Other stuff - figure out later
    private const ISSUER = 'nexus';
    private const AUDIENCE = 'temp-audience';
    private const ALGORITHM = 'HS256';


    //--- PRIVATE

    private static function generateToken($user, $time, $key)
    {
        $payload = [
            'iss' => self::ISSUER,
            'aud' => self::AUDIENCE,
            'iat' => time(), // Issued at
            'exp' => time() + $time, // Expiration time
            'sub' => $user['id']
        ];

        $jwt = JWT::encode($payload, $key, self::ALGORITHM);
        return $jwt;
    }

    private static function validateToken($token, $key)
    {
        try {
            $decoded = JWT::decode($token, new Key($key, self::ALGORITHM));
            // Validate the refresh token and check if the user is still authorized

            return $decoded;
        } catch (Exception $e) {
            // Handle error (e.g., token expired or invalid)
            return null;
        }
    }

    //--- PUBLIC

    // User: 1 access token(1h authorization) while refresh token(7d authentification) is valid
    public static function issueTokens($user)
    {
        $accessToken = self::generateToken($user, self::ACCESS_TIME, self::ACCESS_KEY);

        $refreshToken = self::generateToken($user, self::REFRESH_TIME, self::REFRESH_KEY);

        return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
    }

    // Valid Refresh token ? new Access token : error
    public static function refreshAccessToken($refreshToken)
    {
        $decoded = self::validateToken($refreshToken, self::REFRESH_KEY);

        return self::generateToken($decoded->user, self::ACCESS_TIME, self::ACCESS_KEY);
    }

    public static function generateAccessToken($forUser)
    {
    }

    public static function validateRefreshToken($refreshToken)
    {
        try {
            $decoded = JWT::decode($refreshToken, new Key(self::REFRESH_KEY, self::ALGORITHM));

            return self::generateAccessToken($decoded->user);
        } catch (Exception $e) {
            return null;
        }
    }

    //--- PUBLIC NON-MODULAR

    // Access Token: Short-lived
    // public static function generateAccessToken($user)
    // {
    //     $payload = [
    //         'iss' => self::ISSUER,
    //         'aud' => self::AUDIENCE,
    //         'iat' => time(),
    //         'exp' => time() + 3600,
    //         'sub' => $user['id']
    //     ];

    //     $jwt = JWT::encode($payload, self::ACCESS_KEY, self::ALGORITHM);
    //     return $jwt;
    // }

    // Refresh Token: Long-lived
    // public static function generateRefreshToken($user)
    // {
    //     $payload = [
    //         'iss' => self::ISSUER,
    //         'aud' => self::AUDIENCE,
    //         'iat' => time(),
    //         'exp' => time() + 604800,
    //         'sub' => $user['id']
    //     ];

    //     return JWT::encode($payload, self::REFRESH_KEY, self::ALGORITHM);
    // }
}
