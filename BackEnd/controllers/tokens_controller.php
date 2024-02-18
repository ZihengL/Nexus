<?php

require_once "$path/controllers/base_controller.php";
require_once $path . '/models/token_model.php';
require_once $path . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController extends BaseController
{
    // COLUMNS
    public const USER_ID = 'user_id';
    public const EXPIRY_DATE = 'expiry_date';
    public const REVOCATION_DATE = 'revocation_date';

    // TIMEOUT
    private const ACCESS_TIMEOUT = 3600;
    private const REFRESH_TIMEOUT = 86400;

    // private $model;
    private $access_key;        // Permission for secure API calls
    private $refresh_key;      // Authentified for access keys issuing
    private $algorithm;
    private $issuer;
    private $audience;

    // CONSTRUCTOR

    public function __construct($central_controller, $pdo)
    {
        parent::__construct($central_controller, new RevokedTokenModel($pdo));

        $this->access_key = $_ENV['JWT_ACCESS_KEY'];
        $this->refresh_key = $_ENV['JWT_REFRESH_KEY'];
        $this->algorithm = $_ENV['JWT_ALGORITHM'];
        $this->issuer = $_ENV['JWT_ISSUER'];
        $this->audience = $_ENV['JWT_AUDIENCE'];
    }

    // GENERATION

    private function generateToken($user_id, $isRefresh = false)
    {
        $current_time = time();
        if ($isRefresh) {
            $key = $this->refresh_key;
            $expiration_time = self::REFRESH_TIMEOUT;
        } else {
            $key = $this->access_key;
            $expiration_time = self::ACCESS_TIMEOUT;
        }

        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $current_time, // Issued at
            'exp' => $current_time + $expiration_time, // Expiration
            'sub' => $user_id
        ];

        return JWT::encode($payload, $key, $this->algorithm);
    }

    public function generateAccessToken($refresh_token)
    {
        if ($this->validateRefreshToken($refresh_token)) {
            return $this->generateToken($refresh_token->sub, false);
        }
    }

    public function generateRefreshToken($user_id)
    {
        return $this->generateToken($user_id, true);
    }

    // Prefer not using.
    public function generateTokenPair($user_id)
    {
        $refresh_token = $this->generateRefreshToken($user_id);
        $access_token = $this->generateAccessToken($refresh_token);

        return ['access_token' => $access_token, 'refresh_token' => $refresh_token];
    }

    // VALIDATION

    private function validateToken($token, $key)
    {
        if ($this->isRevoked($token)) return false;

        try {
            return (array) JWT::decode($token, new Key($key, $this->algorithm));
        } catch (Exception $e) {
            return false;
        }
    }

    private function validateTokenByType($token, $is_refresh = false)
    {
        $key = $is_refresh ? $this->refresh_key : $this->access_key;

        return $this->validateToken($token, $key);
    }

    public function isExpired($token, $is_refresh = false)
    {
        $decoded = $this->validateToken($token, $is_refresh ? $this->refresh_key : $this->access_key);

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

    public function revokeToken($token, $is_refresh)
    {
        $decoded = $this->validateTokenByType($token, $is_refresh);

        if ($decoded) {
            $decoded['id'] = $token;
            $decoded['rev'] = time();

            return $this->model->create($decoded);
        }

        return false;
    }

    public function revokeTokens($tokens)
    {
        $access_token = $tokens['access_token'];
        $refresh_token = $tokens['refresh_token'];

        return $this->revokeToken($access_token, false) && $this->revokeToken($refresh_token, true);
    }

    public function deleteExpiredTokens()
    {
        return $this->model->deleteExpiredTokens();
    }
}
