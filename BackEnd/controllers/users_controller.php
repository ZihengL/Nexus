<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/user_model.php";

class UsersController extends BaseController
{
    // private $model;
    protected $table = "user";
    protected $id = "id";
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
        parent::__construct($central_controller, $pdo, new UserModel($pdo));
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getOneMatchingColumn($column, $value, $included_columns = [])
    {
        return $this->model->getOne($column, $value, $included_columns);
    }

    public function getAllUsers($included_columns = [])
    {
        return $this->model->getAll($included_columns);
    }

    public function getById($id, $included_columns = [])
    {
        return $this->model->getAll($id, $this->id, $included_columns);
    }

    public function getByEmail($email)
    {
        return $this->model->getAll($this->email, $email);
    }

    public function getByName($name)
    {
        return $this->model->getAll($this->name, $name);
    }

    public function getByLastName($lastname)
    {
        return $this->model->getAll($this->lastname, $lastname);
    }

    public function applyFiltersAndSorting($filters, $sorting, $includedColumns)
    {
        return $this->model->applyFiltersAndSorting($filters, $sorting, $includedColumns);
    }


    // ONLY FOR TESTING, DELETE IN FUTURE
    public function getAll_users($included_columns = [])
    {
        return $this->model->getAll_users($included_columns);
    }

    // OTHER CRUDS

    // Returns tokens to log the user in
    public function createUser($data)
    {
        $tokens_controller = $this->central_controller->tokens_controller;

        if ($this->model->create($data)) {
            $user = $this->getOneMatchingColumn($this->email, $data[$this->email], [$this->id]);

            return $tokens_controller->generateTokenPair($user[$this->id]);
        }
    }

    public function updateUser($id, $data, $tokens)
    {
        $user = $this->model->getById($id);

        if ($user && $this->isAuthenticated($tokens)) {
            return $this->model->update($id, $data);
        }

        return false;
    }

    public function deleteUser($id, $tokens)
    {
        $user = $this->model->getById($id);

        if ($user && $this->isAuthenticated($tokens)) {
            return $this->model->delete($id);
        }

        return false;
    }

    public function userExists($data)
    {
        return isset($data[$this->email]) && !empty($this->getByEmail($data[$this->email]));
    }

    //  ACCESS & SECURITY

    public function login($email, $password)
    {
        $tokens_controller = $this->central_controller->tokens_controller;
        $user = $this->getByEmail($email);

        if ($this->verifyUser($user, $email, $password)) {
            return $tokens_controller->generateTokenPair($user[$this->id]);
        }

        return false;
    }

    public function logout($tokens)
    {
        $tokens_controller = $this->central_controller->tokens_controller;

        return $tokens_controller->revokeTokens($tokens);
    }

    private function verifyUser($user, $email, $password)
    {
        return $user &&
            $user[$this->email] === $email &&
            password_verify($password, $user[$this->password]);
    }

    public function isAuthenticated($tokens)
    {
        $tokens_controller = $this->central_controller->tokens_controller;

        return $tokens_controller->validateRefreshToken($tokens['refresh_token']);
    }
}
