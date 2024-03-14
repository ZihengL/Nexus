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
        $this->restricted_columns = ['password', 'email', 'phoneNumber', 'isAdmin'];
        $specific_actions = [
            'authenticate' => false,
            'login' => false,
            'logout' => true,
            'update' => true,
            'delete' => true
        ];

        parent::__construct($central_controller, $specific_actions);
    }

    public function userExists($data)
    {
        return isset($data[$this->email]) &&
            !empty($this->model->getOne(column: $this->email, value: $data[$this->email]));
    }

    private function getByEmail($email)
    {
        return $this->model->getOne(column: $this->email, value: $email);
    }

    //  ACCESS & SECURITY

    public function authenticate($data)
    {
        ['id' => $id, 'tokens' => $tokens] = $data;

        if ($authenticated_tokens = $this->authenticateUser($id, $tokens)) {
            return $authenticated_tokens;
        }
    }

    // Do this if user needs to do a fresh login
    public function login($data)
    {
        ['email' => $email, 'password' => $password] = $data;

        $user = $this->model->getOne(column: $this->email, value: $email);

        $tokens_controller = $this->getTokensController();
        if ($tokens = $tokens_controller->generateTokensOnValidation($user, $email, $password)) {
            $this->model->update($user['id'], ['isOnline' => true]);

            return ['user' => $user, 'tokens' => $tokens];
        }

        return false;
    }

    public function logout($data)
    {
        ['id' => $id, 'tokens' => $tokens] = $data;

        return $this->getTokensController()->revokeAccess(tokens: $tokens) &&
            $this->model->update($id, ['isOnline' => false]);
    }
}
