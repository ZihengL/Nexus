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
        $this->restricted_columns = $this->model->getColumns(true);
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

        $payload[self::SHA] = hash($this->hashing_alg, $jwt);
        if ($this->model->create($payload))
            return $jwt;

        return null;
    }

    private function generateAccessToken($user_id)
    {
        $payload = $this->createPayload($user_id, self::ACCESS_TIME);

        return JWT::encode($payload, $this->access_key, $this->encoding_alg);
    }

    protected function refreshAccessToken($refresh_token)
    {
        if ($decoded = $this->decodeToken($refresh_token, true))
            return $this->generateAccessToken($decoded[self::SUB]);

        return null;
    }

    public function generateTokensOnValidation($user, $email, $password)
    {
        if ($this->validateCredentials($user, $email, $password)) {
            $id = $user['id'];

            $refresh_jwt = $this->generateRefreshToken($id);
            $access_jwt = $this->generateAccessToken($id);

            return [self::ACCESS => $access_jwt, self::REFRESH => $refresh_jwt];
        }

        return null;
    }


    /*******************************************************************/
    /*************************** VALIDATION ****************************/
    /*******************************************************************/

    protected function validateCredentials($user, $email, $password)
    {
        if (!$user)
            throw new Exception("No user found with email '$email'.");

        if ($user['email'] !== $email || !password_verify($password, $user['password'])) {
            $this->revokeAccess(id: $user['id']);
            throw new Exception("Provided credentials mismatch.");
        }

        return true;
    }

    public function validateRefreshToken($id, $refresh_token)
    {
        if ($stored = $this->getBySha($refresh_token))
            return $stored[self::EXP] > time() && $stored[self::SUB] === $id;

        return false;
    }

    public function validateAccessToken($user_id, $access_token)
    {
        if ($decoded = $this->decodeToken($access_token, false)) {
            // DOUBLE EQUALS IGNORES IF USER ID IS IN STRING FORMAT
            // printall($decoded);
            // printer($decoded[self::EXP] . " " . time());
            // echo ($decoded[self::SUB] == $user_id && $decoded[self::EXP] > time()) ? "truc " : "pas truc";
            return $decoded[self::SUB] == $user_id && $decoded[self::EXP] > time();
        }

        return false;
    }

    // Doesn't refresh access; revokes all access from user if not valid
    // public function validateTokens($user_id, $jwts)
    // {
    //     [self::ACCESS =>  $access_token, self::REFRESH => $refresh_token] = $jwts + [null, null];

    //     if (($access_token && $this->validateAccessToken($access_token)) ||
    //         ($refresh_token && $this->validateRefreshToken($user_id, $refresh_token))
    //     ) {
    //         return true;
    //     }

    //     $this->revokeAccess(id: $user_id);
    //     throw new Exception("Invalid authentication tokens provided for User id '$user_id'.");
    // }

    // public function validateAccess($user_id, $access_token)
    // {
    //     if ($this->validateAccessToken($access_token)) {
    //         return true;
    //     }

    //     throw new Exception("Invalid authentication tokens provided for User id '$user_id'.");
    // }

    // Refreshes access
    // public function authenticateTokens($user_id, $refresh_token)
    // {
    //     // [self::ACCESS =>  $access_token, self::REFRESH => $refresh_token] = $jwts + [null, null];


    //     // if ($access_token && $this->validateAccessToken($access_token))
    //     //     return $jwts;

    //     if ($refresh_token && $this->validateRefreshToken($user_id, $refresh_token)) {
    //         return $this->refreshAccessToken($refresh_token);
    //         // $jwts[self::ACCESS] = $this->refreshAccessToken($refresh_token);
    //         // return $jwts;
    //     }

    //     $this->getUsersController()->logout(['id' => $user_id, 'refresh_token' => $refresh_token]);
    //     throw new Exception("Invalid authentication tokens provided for User id '$user_id'.");
    // }

    public function revokeAccess($id = null, $tokens = null)
    {
        // return $this->getByHashcode($tokens['refresh_token']);

        return ($tokens && $this->deleteByHash($tokens)) ||
            ($id && $this->deleteAllFromUser($id));
    }

    public function revokeRefreshToken($refresh_token) {
        if ($stored = $this->getBySha($refresh_token)) {
            return $this->model->delete($stored['id']);
        }

        throw new Exception("Refresh token not found.");
    }

    public function revokeAllAccess($user_id) {
        return $this->deleteAllFromUser($user_id);
    }


    /*******************************************************************/
    /***************************** CRUDS *******************************/
    /*******************************************************************/

    public function create($data)
    {
        throw new Exception('Access restricted.');
    }

    public function update($data)
    {
        throw new Exception('Access restricted.');
    }

    public function delete($data)
    {
        throw new Exception('Access restricted.');
    }

    private function deleteByHash($jwts)
    {
        if ($jwts && isset($jwts[self::REFRESH]))
            if ($stored = $this->getBySha($jwts[self::REFRESH])) {
                return $this->model->delete($stored[self::ID]);
            }

        return false;
    }

    private function deleteAllFromUser($user_id)
    {
        if ($this->model->deleteAllFromUser($user_id))
            return true;

        return false;
    }


    /*******************************************************************/
    /***************************** TOOLS *******************************/
    /*******************************************************************/

    public function getTokenSub($access_token = null, $refresh_token = null)
    {
        if ($access_token)
            return $this->decodeToken($access_token, false)[self::SUB];

        if ($refresh_token)
            return $this->decodeToken($refresh_token, true)[self::SUB];

        return false;
    }

    protected function getBySub($user_id)
    {
        return $this->model->getOne(column: self::SUB, value: $user_id);
    }

    protected function getBySha($refresh_jwt)
    {
        return $this->model->getOne(column: self::SHA, value: hash($this->hashing_alg, $refresh_jwt));
    }

    public function deleteExpiredTokens()
    {
        return $this->model->deleteExpiredTokens();
    }
}
