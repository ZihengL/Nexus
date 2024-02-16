<?php

require_once "$path/models/user_model.php";

class UsersController
{
    private $model;
    private $tokens_controller;
    protected $table = "user";
    protected $email = "email";
    protected $name = "name";
    protected $lastname = "lastname";
    protected $phonenumber = "phone_number";
    protected $privilege = "privilege";
    protected $description = "description";

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

    public function getAllUsers($included_columns = [])
    {
        return $this->model->getAll($included_columns);
    }

    public function getById($id, $included_columns = [])
    {
        return $this->model->getAll($id, $included_columns);
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

    public function applyFiltersAndSorting($filters, $sorting)
    {
        return $this->model->applyFiltersAndSorting($filters, $sorting);
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
        return isset($data['email']) && !empty($this->getByEmail($data['email']));
    }

    //  ACCESS & SECURITY

    public function login($email, $password)
    {
        $user = $this->getByEmail($email);

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
        return $this->tokens_controller->validateRefreshToken($tokens['refresh_token']);
    }
}
