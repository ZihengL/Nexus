<?php

require_once "$path/models/user_model.php";

class UsersController
{
    private $model;
    protected $table = "user";
    protected $email = "email";
    protected $name = "name";
    protected $lastname = "lastname";
    protected $phonenumber = "phone_number";
    protected $privilege = "privilege";
    protected $description = "description";
    private $tokens_controller;

    public function __construct($pdo, $tokens_controller)
    {
        $this->model = new UserModel($pdo);
        $this->tokens_controller = $tokens_controller;
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getOneMatchingColumn($column, $value, $included_columns = [])
    {
        return $this->model->getOne($column, $value, $included_columns);
    }

    public function getUserById($id, $columns = [])
    {
        return $this->model->getById($id);
    }

    public function getUserByEmail($email, $columnName)
    {
        return $this->model->getOne($this->email, $email);
    }

    public function getUsersByName($name, $columnName)
    {
        return $this->model->get($this->name, $name);
    }

    public function getUsersByLastName($lastname, $columnName)
    {
        return $this->model->get($this->lastname, $columnName);
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

    public function getById($id)
    {
        return $this->model->getById($id);
    }

    // OTHER CRUDS

    public function createUser($data)
    {
        return $this->model->create($data);
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
        return isset($data['email']) && !empty($this->getUserByEmail($data['email'], ['email']));
    }

    //  ACCESS & SECURITY

    public function login($email, $password)
    {
        $user = $this->getOneMatchingColumn($email, 'email');

        if ($this->verifyUser($user, $email, $password)) {
            return $this->tokens_controller->generateTokenPair($user['id']);
        }

        return false;
    }

    public function logout($tokens)
    {
        return $this->tokens_controller->revokeTokens($tokens);
    }

    private function verifyUser($user, $email, $password)
    {
        return $user &&
            $user['email'] === $email &&
            password_verify($password, $user['password']);
    }

    public function isAuthenticated($tokens)
    {
        return $this->tokens_controller->validateRefreshToken($tokens);
    }
}
