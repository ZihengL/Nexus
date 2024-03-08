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

        $specific_actions = ['login', 'logout', 'authenticate'];
        $privileged_actions = ['update', 'delete'];
        parent::__construct($central_controller, $specific_actions, $privileged_actions);
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

    // OTHER CRUDS

    // Returns tokens to log the user in
    // TODO: CREATE USER FOLDER
    // public function create($data)
    // {
    //     if ($this->model->create($data)) {
    //         $user = $this->model->getOne($this->email, $data[$this->email]);

    //         if ($user) {

    //             return true;
    //         }
    //     }

    //     return false;
    // }

    public function update($id, $tokens = null, ...$data)
    {
        if ($validated_tokens = $this->authenticateUser($id, $tokens)) {
            $this->model->update(id: $id, data: $data);

            return $validated_tokens;
        }

        return false;
    }

    public function delete($id, $tokens = null, ...$data)
    {
        return $this->validateUser($id, $tokens) && $this->model->delete($id);
    }

    //  ACCESS & SECURITY

    // Do this if user needs to do a fresh login
    public function login($data)
    {
        

        $user = $this->model->getOne(column: $this->email, value: $email);
        $tokens_controller = $this->getTokensController();

        if ($tokens = $tokens_controller->generateTokensOnValidation($user, $email, $password)) {
            return ['user' => $user, 'tokens' => $tokens];
        }
    }

    public function logout($data)
    {
        return $this->validateUser($id, $tokens) &&
            $this->getTokensController()->revokeAccess(tokens: $tokens);
    }
}
