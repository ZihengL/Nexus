<?php

require_once "$path/controllers/base_controller.php";
require_once $path . '/models/token_model.php';
require_once $path . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController extends BaseController
{
    // COLUMNS
    protected const ID = 'id';
    protected const SUB = 'sub';
    protected const EXP = 'exp';
    protected const REV = 'rev';

    // TIMEOUT
    private const ACCESS_TIMEOUT = 3600;
    private const REFRESH_TIMEOUT = 86400;

    // TOKENS
    private const ACCESS = 'access_token';
    private const REFRESH = 'refresh_token';

    // ENV
    private $access_key;        // Permission for secure API calls
    private $refresh_key;      // Authentified for access keys issuing
    private $algorithm;
    private $issuer;
    private $audience;

    // CONSTRUCTOR

    public function __construct($central_controller, $pdo)
    {
        $this->model = new RevokedTokenModel($pdo);
        parent::__construct($central_controller);

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
        $decoded = $this->validateToken($refresh_token, $this->refresh_key);

        if ($decoded) {
            return $this->generateToken($decoded[self::SUB], false);
        }
    }

    private function generateRefreshToken($user_id)
    {
        return $this->generateToken($user_id, true);
    }

    // Prefer not using.
    public function generateTokenPair($user_id)
    {
        $refresh_token = $this->generateRefreshToken($user_id);
        $access_token = $this->generateAccessToken($refresh_token);

        return [self::ACCESS => $access_token, self::REFRESH => $refresh_token];
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

        return !$decoded || $decoded[self::EXP] < time();
    }

    public function validateAccessToken($id, $access_token)
    {
        $decoded = $this->validateToken($access_token, $this->access_key);

        return $decoded && $decoded[self::SUB] === $id;
    }

    public function validateRefreshToken($id, $refresh_token)
    {
        $decoded = $this->validateToken($refresh_token, $this->refresh_key);

        return $decoded && $decoded[self::SUB] === $id;
    }

    public function validateTokens($id, $tokens)
    {
        $access_token = $tokens[self::ACCESS];
        $refresh_token = $tokens[self::REFRESH];

        return $this->validateAccessToken($id, $access_token) &&
            $this->validateRefreshToken($id, $refresh_token);
    }

    // DATABASE

    public function getAll()
    {
        return $this->model->getAll();
    }

    public function isRevoked($refresh_token)
    {
        return $this->model->getOne(self::ID, $refresh_token);
    }

    public function revokeToken($token, $is_refresh)
    {
        $decoded = $this->validateTokenByType($token, $is_refresh);

        if ($decoded) {
            $decoded[self::ID] = $token;
            $decoded[self::REV] = time();

            return $this->model->create($decoded);
        }

        return false;
    }

    public function revokeTokens($tokens)
    {
        $access_token = $tokens[self::ACCESS];
        $refresh_token = $tokens[self::REFRESH];

        return $this->revokeToken($access_token, false) && $this->revokeToken($refresh_token, true);
    }

    public function deleteExpiredTokens()
    {
        return $this->model->deleteExpiredTokens();
    }
}
