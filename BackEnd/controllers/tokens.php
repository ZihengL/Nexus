<?php
require_once $path . '/models/token.php';
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController
{
    private static $instance = null;

    // COLUMNS
    public const USER_ID = 'user_id';
    public const EXPIRY_DATE = 'expiry_date';
    public const REVOCATION_DATE = 'revocation_date';

    // TIMEOUT
    private const ACCESS_TIMEOUT = 3600;
    private const REFRESH_TIMEOUT = 604800;

    private $model;
    private $access_key;        // Permission for secure API calls
    private $refresh_key;      // Authentified for access keys issuing
    private $algorithm;
    private $issuer;
    private $audience;

    // TODO: Create revocated tokens list. Tokens list stay in memory somewhere.

    // CONSTRUCTOR

    private function __construct($pdo, $env)
    {
        $this->model = new RevokedTokenModel($pdo);

        $this->access_key = $env->access_key;
        $this->refresh_key = $env->refresh_key;
        $this->algorithm = $env->algorithm;
        $this->issuer = $env->issuer;
        $this->audience = $env->audience;
    }

    public static function getInstance($pdo, $env)
    {
        if (self::$instance == null) {
            self::$instance = new TokensController($pdo, $env);
        }

        return self::$instance;
    }

    // GENERATION

    private function generateToken($user_id, $key, $expiration_time, $other_content = [])
    {
        $current_time = time();

        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $current_time, // Issued at
            'exp' => $current_time + $expiration_time, // Expiration time
            'sub' => $user_id
        ];

        return JWT::encode($payload, $key, $this->algorithm);
    }

    public function generateAccessToken($user)
    {
        return $this->generateToken($user['id'], $this->access_key, self::ACCESS_TIMEOUT);
    }

    public function generateRefreshToken($user)
    {
        return $this->generateToken($user['id'], $this->refresh_key, self::REFRESH_TIMEOUT);
    }

    public function generateTokens($user)
    {
        $access_token = $this->generateAccessToken($user);
        $refresh_token = $this->generateRefreshToken($user);

        return ['access_token' => $access_token, 'refresh_token' => $refresh_token];
    }

    // Valid Refresh token ? new Access token : error
    public function refreshAccessToken($refresh_token)
    {
        if ($this->validateRefreshToken($refresh_token)) {
            return $this->generateAccessToken($refresh_token->sub);
        }

        return false;
    }

    // VALIDATION

    private function validateToken($token, $key)
    {
        try {
            return JWT::decode($token, new Key($key, $this->algorithm));
        } catch (Exception $e) {
            // TODO: IMPLEMENT ERROR HANDLING
            return null;
        }
    }

    public function validateAccessToken($access_token)
    {
        return $this->validateToken($access_token, $this->access_key);
    }

    public function validateRefreshToken($refresh_token)
    {
        return $this->validateToken($refresh_token, $this->refresh_key);
    }

    public function validateTokens($tokens)
    {
        $access_token = $tokens->access_token;
        $refresh_token = $tokens->refresh_token;

        return $this->validateAccessToken($access_token) &&
            $this->validateRefreshToken($refresh_token);
    }

    // REVOCATION MANAGEMENT

    public function deleteExpiredTokens()
    {
        return $this->model->deleteExpiredTokens();
    }
}
