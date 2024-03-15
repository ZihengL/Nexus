<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/users_model.php";

class UsersController extends BaseController
{
    protected $id = "id";
    protected $drive_id = "drive_id";
    protected $password = "password";
    protected $email = "email";
    protected $phoneNumber = "phoneNumber";
    protected $picture = "picture";
    protected $isAdmin = "isAdmin";
    protected $isOnline = "isOnline";
    protected $description = "description";
    protected $name = "name";
    protected $lastname = "lastname";
    protected $privilege = "privilege";


    public function __construct($central_controller, $pdo)
    {
        $this->model = new UsersModel($pdo);
        $this->restricted_columns = ['email', 'password', 'phoneNumber', 'isAdmin'];
        $specific_actions = [
            'authenticate' => false,
            'login' => false,
            'logout' => false,
            'update' => true,
            'delete' => true
        ];

        parent::__construct($central_controller, $specific_actions);
    }

    public function userExists($data)
    {
        return isset($data[$this->email]) &&
            !empty($this->getByEmail($data[$this->email]));
    }

    private function getByEmail($email)
    {
        return $this->model->getOne(column: $this->email, value: $email);
    }

    //  ACCESS & SECURITY

    public function authenticate($data)
    {
        ['id' => $id, 'refresh_token' => $refresh_token] = $data;

        if ($this->authenticateUser($id, $refresh_token)) {
            return $this->getTokensController()->refreshAccessToken($refresh_token);
        }

        return false;
    }

    // Do this if user needs to do a fresh login
    public function login($data)
    {
        ['email' => $email, 'password' => $password] = $data;
        $user = $this->getByEmail($email);

        $tokens_controller = $this->getTokensController();
        if ($tokens = $tokens_controller->generateTokensOnValidation($user, $email, $password)) {
            $this->model->update($user['id'], ['isOnline' => true]);

            return ['user' => $user, 'tokens' => $tokens];
        }

        return false;
    }

    public function logout($data)
    {
        ['id' => $id, 'refresh_token' => $refresh_token] = $data;

        if ($this->getTokensController()->revokeRefreshToken($refresh_token)) {
            $this->model->update($id, ['isOnline' => false]);

            return true;
        }

        return false;
    }
}
