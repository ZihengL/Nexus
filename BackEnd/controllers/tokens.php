<?php
require_once $path . '/models/token.php';
require_once $path . '/vendor/autoload.php';

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
    private const REFRESH_TIMEOUT = 86400;

    private $model;
    private $access_key;        // Permission for secure API calls
    private $refresh_key;      // Authentified for access keys issuing
    private $algorithm;
    private $issuer;
    private $audience;

    // TODO: Create revocated tokens list. Tokens list stay in memory somewhere.

    // CONSTRUCTOR

    private function __construct($pdo)
    {
        $this->model = new RevokedTokenModel($pdo);

        $this->access_key = $_ENV['JWT_ACCESS_KEY'];
        $this->refresh_key = $_ENV['JWT_REFRESH_KEY'];
        $this->algorithm = $_ENV['JWT_ALGORITHM'];
        $this->issuer = $_ENV['JWT_ISSUER'];
        $this->audience = $_ENV['JWT_AUDIENCE'];
    }

    public static function getInstance($pdo)
    {
        if (self::$instance == null) {
            self::$instance = new TokensController($pdo);
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
            // 'exp' => $current_time + 5,
            'sub' => $user_id
        ];

        return JWT::encode($payload, $key, $this->algorithm);
    }

    public function generateAccessToken($refresh_token)
    {
        if ($this->validateRefreshToken($refresh_token)) {
            return $this->generateToken($refresh_token->sub, $this->access_key, self::ACCESS_TIMEOUT);
        }
    }

    public function generateRefreshToken($user_id)
    {
        return $this->generateToken($user_id, $this->refresh_key, self::REFRESH_TIMEOUT);
    }

    public function generateTokenPair($user_id)
    {
        $refresh_token = $this->generateRefreshToken($user_id);
        $access_token = $this->generateAccessToken($refresh_token);

        return ['access_token' => $access_token, 'refresh_token' => $refresh_token];
    }

    // Valid Refresh token ? new Access token : error
    // public function refreshAccessToken($refresh_token)
    // {
    //     if ($this->validateRefreshToken($refresh_token)) {
    //         return $this->generateAccessToken($refresh_token);
    //     }

    //     return false;
    // }

    // VALIDATION

    private function validateToken($token, $key)
    {
        if ($this->isRevoked($token)) return false;

        try {
            return (array) JWT::decode($token, new Key($key, $this->algorithm));
        } catch (Exception $e) {
            // TODO: IMPLEMENT ERROR HANDLING
            return false;
        }
    }

    public function isExpired($token, $isRefresh = false)
    {
        $decoded = $this->validateToken($token, $isRefresh ? $this->refresh_key : $this->access_key);


        return !$decoded || $decoded['exp'] < time();
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

    // DATABASE

    public function getAll()
    {
        return $this->model->getAll();
    }

    public function isRevoked($refresh_token)
    {
        return $this->model->getById($refresh_token);
    }

    public function revokeToken($refresh_token)
    {
        $decoded = $this->validateRefreshToken($refresh_token);

        if ($decoded) {
            $decoded['id'] = $refresh_token;
            $decoded['rev'] = time();

            return $this->model->create($decoded);
        }

        return false;
    }

    public function deleteExpiredTokens()
    {
        return $this->model->deleteExpiredTokens();
    }

    // GETTERS
}
