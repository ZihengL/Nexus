<?php

require_once "$path/controllers/base_controller.php";
require_once $path . '/models/token_model.php';
require_once $path . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController extends BaseController
{
    // TIMEOUT
    private const ACCESS_TIMEOUT = 3600;
    private const REFRESH_TIMEOUT = 86400;

    // TOKENS TYPES
    private const ACCESS = 'access_token';
    private const REFRESH = 'refresh_token';

    // COLUMNS
    protected const ID = 'id';
    protected const SUB = 'sub';
    protected const EXP = 'exp';
    protected const REV = 'rev';

    // ENV
    private $access_key;        // Permission for secure API calls
    private $refresh_key;      // Authentified for access keys issuing
    private $algorithm;
    private $issuer;
    private $audience;

    // CONSTRUCTOR

    public function __construct($central_controller, $pdo)
    {
        $this->access_key = $_ENV['JWT_ACCESS_KEY'];
        $this->refresh_key = $_ENV['JWT_REFRESH_KEY'];
        $this->algorithm = $_ENV['JWT_ALGORITHM'];
        $this->issuer = $_ENV['JWT_ISSUER'];
        $this->audience = $_ENV['JWT_AUDIENCE'];

        $this->model = new RevokedTokenModel($pdo);
        parent::__construct($central_controller);
    }

    // GENERATION

    private function generateToken($user_id, $key, $expiration_time)
    {
        $current_time = time();

        // if ($isRefresh) {
        //     $key = $this->refresh_key;
        //     $expiration_time = self::REFRESH_TIMEOUT;
        // } else {
        //     $key = $this->access_key;
        //     $expiration_time = self::ACCESS_TIMEOUT;
        // }

        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $current_time, // Issued at
            self::EXP => $current_time + $expiration_time, // Expiration
            self::SUB => $user_id
        ];

        return JWT::encode($payload, $key, $this->algorithm);
    }

    private function generateRefreshToken($user_id)
    {
        return $this->generateToken($user_id, $this->refresh_key, self::REFRESH_TIMEOUT);
    }

    public function generateAccessToken($user_id, $refresh_token)
    {
        if ($this->isValid($user_id, $refresh_token, true)) {
            return $this->generateToken($user_id, $this->access_key, self::ACCESS_TIMEOUT);
        }

        return false;
    }

    public function generateTokens($user_id)
    {
        $refresh_token = $this->generateRefreshToken($user_id);
        $access_token = $this->generateAccessToken($user_id, $refresh_token);

        return [self::ACCESS => $access_token, self::REFRESH => $refresh_token];
    }

    // VALIDATION

    private function decodeToken($token, $key)
    {
        try {
            return (array) JWT::decode($token, new Key($key, $this->algorithm));
        } catch (Exception $e) {
            return false;
        }
    }

    private function isValid($user_id, $token, $is_refresh = false)
    {
        $decoded = $this->decodeToken($token, $is_refresh ? $this->refresh_key : $this->access_key);

        if ($this->isRevoked($token) || !$decoded) {
            return false;
        } elseif ($decoded[self::EXP] < time() && $user_id === $decoded[self::SUB]) {
            return true;
        } else {
            return $this->revokeToken($token, $is_refresh);
        }
    }

    public function validateAccessToken($user_id, $access_token)
    {
        return $this->isValid($user_id, $access_token, false);
    }

    public function validateRefreshToken($user_id, $refresh_token)
    {
        return $this->isValid($user_id, $refresh_token, true);
    }

    public function validateTokens($user_id, $tokens)
    {
        $access_token = $tokens[self::ACCESS];
        $refresh_token = $tokens[self::REFRESH];

        return $this->validateAccessToken($user_id, $access_token) &&
            $this->validateRefreshToken($user_id, $refresh_token);
    }

    // DATABASE

    public function isRevoked($token)
    {
        return $this->model->getOne(self::ID, $token, [self::ID]);
    }

    public function revokeToken($token, $is_refresh = false)
    {
        $decoded = $this->decodeToken($token, $is_refresh);

        if ($decoded) {
            $decoded[self::ID] = $token;
            $decoded[self::REV] = time();
            $this->model->create($decoded);

            return true;
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
