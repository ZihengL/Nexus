<?php

require_once "$path/controllers/base_controller.php";
require_once $path . '/models/tokens_model.php';
require_once $path . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController extends BaseController
{
    // TOKEN TYPES
    private const REFRESH = 'refresh_token';
    private const REFRESH_TIME = 604800;
    private const ACCESS = 'access_token';
    private const ACCESS_TIME = 3600;

    // IN DB
    protected const ID = 'id';
    protected const SUB = 'sub';
    protected const EXP = 'exp';
    protected const SHA = 'sha';

    // NOT IN DB
    protected const ISS = 'iss';
    protected const IAT = 'iat';
    protected const AUD = 'aud';

    // ENV
    private $access_key;
    private $refresh_key;
    private $encoding_alg;
    private $hashing_alg;
    private $issuer;
    private $audience;

    public function __construct($central_controller, $pdo)
    {
        $this->access_key = $_ENV['JWT_ACCESS_KEY'];
        $this->refresh_key = $_ENV['JWT_REFRESH_KEY'];
        $this->encoding_alg = $_ENV['JWT_ENCODING_ALGORITHM'];
        $this->hashing_alg = $_ENV['JWT_HASHING_ALGORITHM'];
        $this->issuer = $_ENV['JWT_ISSUER'];
        $this->audience = $_ENV['JWT_AUDIENCE'];

        $this->model = new TokensModel($pdo);
        parent::__construct($central_controller);
        $this->actions = [];
    }


    /*******************************************************************/
    /************************* ENCODE & DECODE *************************/
    /*******************************************************************/

    private function decodeToken($jwt, $is_refresh = false)
    {
        try {
            $key = $is_refresh ? $this->refresh_key : $this->access_key;

            return (array) JWT::decode($jwt, new Key($key, $this->encoding_alg));
        } catch (Exception $e) {
            return false;
        }
    }

    private function createPayload($user_id, $expiration)
    {
        $issued_at = time();

        return [
            self::SUB => $user_id,
            self::IAT => $issued_at,
            self::EXP => $issued_at + $expiration,
            self::ISS => $this->issuer,
            self::AUD => $this->audience
        ];
    }

    private function generateRefreshToken($user_id)
    {
        $payload = $this->createPayload($user_id, self::REFRESH_TIME);
        $jwt = JWT::encode($payload, $this->refresh_key, $this->encoding_alg);

        return $this->create($jwt, $payload) ? $jwt : null;
    }

    public function refreshAccessToken($refresh_jwt)
    {
        if ($decoded = $this->decodeToken($refresh_jwt, true))
            return $this->generateAccessToken($decoded[self::SUB]);

        return null;
    }

    private function generateAccessToken($user_id)
    {
        $payload = $this->createPayload($user_id, self::ACCESS_TIME);

        return JWT::encode($payload, $this->access_key, $this->encoding_alg);
    }

    public function generateTokensOnValidation($user, $email, $password)
    {
        if ($this->validateUser($user, $email, $password)) {
            $refresh_jwt = $this->generateRefreshToken($user['id']);
            $access_jwt = $this->generateAccessToken($user['id']);

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
        if ($stored = $this->getByHashcode($jwt))
            return $stored[self::EXP] > time() && $stored[self::SUB] === $user_id;

        return false;
    }

    public function validateAccessToken($jwt)
    {
        if ($decoded = $this->decodeToken($jwt))
            return $decoded[self::EXP] > time();

        return false;
    }

    public function validateTokens($user_id, $jwts)
    {
        $access_jwt = $jwts[self::ACCESS];
        $refresh_jwt = $jwts[self::REFRESH];

        if ($this->validateAccessToken($access_jwt))
            return $jwts;

        if ($this->validateRefreshToken($user_id, $refresh_jwt))
            $jwts[self::ACCESS] = $this->refreshAccessToken($refresh_jwt);
        else
            $jwts = null;

        return $jwts;
    }

    public function revokeAccess($user_id, $jwts)
    {
        $access_jwt = $jwts[self::ACCESS];
        $refresh_jwt = $jwts[self::REFRESH];

        if ($this->validateAccessToken($access_jwt) || $this->validateRefreshToken($user_id, $refresh_jwt)) {
            return $this->delete($refresh_jwt, $jwts);
        }

        return false;
    }


    /*******************************************************************/
    /**************************** DATABASE *****************************/
    /*******************************************************************/

    public function getTokenSub($access_token = null, $refresh_token = null)
    {
        if ($access_token)
            return $this->decodeToken($access_token, false)[self::SUB];

        if ($refresh_token)
            return $this->decodeToken($refresh_token, true)[self::SUB];

        return false;
    }

    protected function getByUserId($user_id)
    {
        return $this->getOne(self::SUB, $user_id);
    }

    protected function getByHashcode($jwt)
    {
        return $this->getOne(self::SHA, hash($this->hashing_alg, $jwt));
    }

    public function create($jwt = null, $decoded = null, $jwts = null, ...$data)
    {
        if ($jwt && $decoded) {
            $decoded[self::SHA] = hash($this->hashing_alg, $jwt);

            return parent::create($decoded);
        }

        return false;
    }

    public function update($id, $jwt = null, $jwts = null, ...$data)
    {
        if ($decoded = $this->decodeToken($jwt, true)) {
            $decoded[self::SHA] = hash($this->hashing_alg, $jwt);

            return parent::update($id, $decoded);
        }

        return false;
    }

    public function delete($id, $jwts = null, ...$data)
    {
        if ($jwts && isset($jwts[self::REFRESH]))
            if ($stored = $this->getByHashcode($jwts[self::REFRESH]))
                return parent::delete($stored[self::ID]);

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
