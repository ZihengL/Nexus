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

    /*******************************************************************/
    /************************* ENCODE & DECODE *************************/
    /*******************************************************************/

    private function decodeToken($jwt, $is_refresh = false)
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
        if ($this->create($jwt, $payload)) {
            return $jwt;
        }

        return null;
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

    public function refreshAccessToken($refresh_jwt)
    {
        $decoded = $this->decodeToken($refresh_jwt, true);

        $issued_at = time();
        $decoded[self::IAT] = $issued_at;
        $decoded[self::EXP] = $issued_at + self::ACCESS_TIME;

        return JWT::encode($decoded, $this->access_key, self::ALGORITHM);
    }

    public function generateTokensOnValidation($user, $email, $password)
    {
        if ($this->validateUser($user, $email, $password)) {
            $user_id = $user['id'];

            $refresh_jwt = $this->generateRefreshToken($user_id);
            $access_jwt = $this->generateAccessToken($user_id);

            return [self::ACCESS => $access_jwt, self::REFRESH => $refresh_jwt];
        }

        return null;
    }

    /*******************************************************************/
    /*************************** VALIDATION ****************************/
    /*******************************************************************/

    private function validateUser($user, $email, $password)
    {
        return $user && $user['email'] === $email &&
            password_verify($password, $user['password']);
    }

    public function validateRefreshToken($user_id, $jwt)
    {
        $stored = $this->getByHashcode($jwt);

        return $stored && $stored[self::EXP] > time() &&
            $stored[self::SUB] === $user_id;
    }

    public function validateAccessToken($jwt)
    {
        $decoded = $this->decodeToken($jwt);

        return $decoded && $decoded[self::EXP] > time();
    }

    public function validateTokens($user_id, $jwts)
    {
        $access_jwt = $jwts[self::ACCESS];
        $refresh_jwt = $jwts[self::REFRESH];

        if ($this->validateAccessToken($access_jwt)) {
            return $jwts;
        } elseif ($this->validateRefreshToken($user_id, $refresh_jwt)) {
            $jwts[self::ACCESS] = $this->refreshAccessToken($refresh_jwt);

            return $jwts;
        } else {
            return null;
        }
    }

    /*******************************************************************/
    /**************************** DATABASE *****************************/
    /*******************************************************************/

    protected function getByUserId($user_id)
    {
        return $this->getOne(self::SUB, $user_id);
    }

    protected function getByHashcode($jwt)
    {
        return $this->getOne(self::SHA, hash(self::HASHING, $jwt));
    }

    public function create($jwt, $decoded = null, $jwts = null)
    {
        if ($jwt && $decoded) {
            $decoded[self::SHA] = hash(self::HASHING, $jwt);

            return parent::create($decoded);
        }

        return false;
    }

    public function update($id, $jwt, $jwts = null)
    {
        $decoded = $this->decodeToken($jwt, true);

        if ($decoded) {
            $decoded[self::SHA] = hash(self::HASHING, $jwt);

            return parent::update($id, $decoded);
        }

        return false;
    }

    public function delete($jwt, $jwts = null)
    {
        $stored = $this->getByHashcode($jwt);

        if ($stored) {
            return parent::delete($stored[self::ID]);
        }

        return false;
    }

    public function deleteAllFromUser($user_id, $jwts)
    {
        if ($this->validateRefreshToken($user_id, $jwts[self::REFRESH])) {
            $stored_models = $this->model->getAllMatching([self::SUB => $user_id]);

            foreach ($stored_models as $stored) {
                $this->model->delete($stored[self::ID]);
            }

            return true;
        }

        return false;
    }

    public function deleteExpiredTokens()
    {
        return $this->model->deleteExpiredTokens();
    }
}
