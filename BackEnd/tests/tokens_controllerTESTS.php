<?php

require_once "$path/controllers/base_controller.php";
require_once $path . '/models/token_model.php';
require_once $path . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController extends BaseController
{
    // TOKENS TYPES & CONSTANTS
    private const ACCESS = 'access_token';
    private const ACCESS_TIME = 3600;
    private const REFRESH = 'refresh_token';
    private const REFRESH_TIME = 604800;
    private const ALGORITHM = 'HS256';

    // COLUMNS
    protected const ID = 'id';      // varchar: jwt
    protected const SUB = 'sub';    // int: user id
    protected const EXP = 'exp';    //  int: expiration time
    protected const REV = 'rev';    // bool: is revoked

    // ENV
    private $access_key;
    private $refresh_key;
    private $encoding_alg;
    private $issuer;
    private $audience;


    public function __construct($central_controller, $pdo)
    {
        $this->access_key = $_ENV['JWT_ACCESS_KEY'];
        $this->refresh_key = $_ENV['JWT_REFRESH_KEY'];
        $this->issuer = $_ENV['JWT_ISSUER'];
        $this->audience = $_ENV['JWT_AUDIENCE'];


        // $this->googleClient = new Google_Client();
        // $this->configureGoogleClient();

        $this->model = new TokenModel($pdo);
        parent::__construct($central_controller);
    }

    private function generateJWT($user_id, $is_refresh = false)
    {
        $issued_at = time();
        if ($is_refresh) {
            $expiration_time = $issued_at + self::REFRESH_TIME;
            $key = $this->refresh_key;
        } else {
            $expiration_time = $issued_at + self::ACCESS_TIME;
            $key = $this->access_key;
        }

        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $issued_at,
            self::EXP => $expiration_time,
            self::SUB => $user_id
        ];

        return JWT::encode($payload, $key, $this->encoding_alg);
    }

    private function decodeToken($jwt, $is_refresh = false)
    {
        $key = $is_refresh ? $this->refresh_key : $this->access_key;

        try {
            return (array) JWT::decode($jwt, new Key($key, $this->encoding_alg));
        } catch (Exception $e) {
            return false;
        }
    }

    private function isValid($decoded)
    {
        return false;
    }

    public function isAuthenticated($user_id)
    {
        $token_model = $this->getOne(self::SUB, $user_id);

        return $token_model && !$token_model[self::REV] &&
            $token_model[self::EXP] < time();
    }

    public function validateAccess($jwt)
    {
        $decoded = $this->decodeToken($jwt);

        if ($decoded) {
            if ($this->isAuthenticated($decoded[self::SUB])) {
            }
        }
    }

    public function validateJWT($jwt, $is_refresh = false)
    {
        $decoded = $this->decodeToken($jwt, $is_refresh);

        $expiration_time = $decoded[self::EXP];
        $user_id = $decoded[self::SUB];

        if ($is_refresh) {
        }
    }
}
