<?php

require_once "$path/controllers/base_controller.php";
require_once $path . '/models/token_model.php';
require_once $path . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController extends BaseController
{
    // TOKENS TYPES
    private const REFRESH = 'refresh_token';
    private const REFRESH_TIME = 604800;
    private const ACCESS = 'access_token';
    private const ACCESS_TIME = 3600;
    private const ALGORITHM = 'HS256';
    private const HASHING = 'sha256';

    // COLUMNS
    protected const ID = 'id';
    protected const SUB = 'sub';
    protected const EXP = 'exp';
    protected const SHA = 'sha';    // hashed

    // EXCLUDED IN DB
    protected const ISS = 'iss';
    protected const IAT = 'iat';
    protected const AUD = 'aud';

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

        $this->model = new TokenModel($pdo);
        parent::__construct($central_controller);
    }

    // ENCODE & DECODE

    private function decodeJWT($jwt, $is_refresh = false)
    {
        try {
            $key = $is_refresh ? $this->refresh_key : $this->access_key;

            return (array) JWT::decode($jwt, new Key($key, self::ALGORITHM));
        } catch (Exception $e) {
            return false;
        }
    }

    private function generateRefreshToken($user_id)
    {
        $issued_at = time();
        $payload = [
            self::SUB => $user_id,
            self::IAT => $issued_at,
            self::EXP => $issued_at + self::REFRESH_TIME,
            self::ISS => $this->issuer,
            self::AUD => $this->audience
        ];
        $jwt = JWT::encode($payload, $this->refresh_key, self::ALGORITHM);

        $payload[self::SHA] = hash(self::HASHING, $jwt);
        $this->create($payload);

        return $jwt;
    }

    private function generateAccessToken($user_id)
    {
        $issued_at = time();
        $payload = [
            self::SUB => $user_id,
            self::IAT => $issued_at,
            self::EXP => $issued_at + self::ACCESS_TIME,
            self::ISS => $this->issuer,
            self::AUD => $this->audience
        ];

        return JWT::encode($payload, $this->access_key, self::ALGORITHM);
    }

    private function refreshAccessToken($refresh_token)
    {
        $decoded = $this->decodeJWT($refresh_token, true);

        $issued_at = time();
        $decoded[self::IAT] = $issued_at;
        $decoded[self::EXP] = $issued_at + self::ACCESS_TIME;

        return JWT::encode($decoded, $this->access_key, self::ALGORITHM);
    }

    public function generateTokens($user_id)
    {
        $refresh_token = $this->generateRefreshToken($user_id);
        $access_token = $this->refreshAccessToken($refresh_token);

        return [self::ACCESS => $access_token, self::REFRESH => $refresh_token];
    }

    // VALIDATION

    public function validateRefreshToken($jwt, $user_id)
    {
        $stored = $this->getByUserId($user_id);

        if ($stored && $stored[self::EXP] < time()) {
            $hashed_jwt = hash(self::HASHING, $jwt);

            return hash_equals($stored[self::SHA], $hashed_jwt);
        }

        return false;
    }

    public function validateAccessToken($jwt)
    {
        $decoded = $this->decodeJWT($jwt);

        return $decoded && $decoded[self::EXP] < time();
    }

    public function validateTokens($jwts, $user_id)
    {
        $refresh_jwt = $jwts[self::REFRESH];
        $access_jwt = $jwts[self::ACCESS];

        if ($this->validateRefreshToken($refresh_jwt, $user_id)) {
            if (!$this->validateAccessToken($access_jwt)) {
                $access_jwt = $this->refreshAccessToken($refresh_jwt);
            }

            return [self::ACCESS => $access_jwt, self::REFRESH => $refresh_jwt];
        }

        return false;
    }

    // DATABASE

    protected function getByUserId($user_id)
    {
        return $this->getOne(self::SUB, $user_id);
    }

    protected function create($decoded)
    {
        if ($decoded) {
            $decoded[self::SHA] = hash(self::HASHING, $decoded);

            return parent::create($decoded);
        }

        return false;
    }

    protected function update($user_id, $jwt)
    {
        $decoded = $this->decodeJWT($jwt, true);

        if ($decoded) {
            return parent::update($user_id, $decoded);
        }

        return false;
    }

    protected function delete($user_id)
    {
        $stored = $this->getByUserId($user_id);

        if ($stored) {
            return parent::delete($stored[self::ID]);
        }

        return false;
    }

    public function deleteExpiredTokens()
    {
        return $this->model->deleteExpiredTokens();
    }
}
