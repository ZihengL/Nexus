<?php

// require_once "$path/controllers/base_controller.php";
require_once "$path/models/user_model.php";

class UsersController
{
    private $model;
    protected $tableName = "user";
    protected $emailColumn = "email";
    protected $nameColumn = "name";
    protected $lastNameColumn = "lastname";
    protected $phoneNumberColumn = "phone_number";
    protected $privilegeColumn = "privilege";
    protected $descriptionColumn = "description";
    private $token_manager;

    public function __construct($pdo, $token_manager)
    {
        $this->model = new UserModel($pdo);
        $this->token_manager = $token_manager;
    }

    public function getUserById($id, $columns = [])
    {
        return $this->model->getById($id);
    }

    public function getUserByEmail($email, $columnName)
    {
        return $this->model->getOne($this->emailColumn, $email);
    }

    public function getUsersByName($name, $columnName)
    {
        return $this->model->get($this->nameColumn, $name);
    }

    public function getUsersByLastName($lastname, $columnName)
    {
        return $this->model->get($this->lastName, $lastname);
    }

    public function applyFiltersAndSorting( $filters, $sorting){
        return $this->model->applyFiltersAndSorting($filters , $sorting );
    }
    

    // ONLY FOR TESTING, DELETE IN FUTURE
    public function getAllUsers($columns = [])
    {
        return $this->model->getAll();
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

    public function updateUser($id, $data)
    {
        $user = $this->model->getById($id);

        // if ($this->isAuthenticated() && $user && $user['id'] === $_SESSION['user']['id']) {
        return $this->model->update($id, $data);
        // }

        // return false;
    }

    public function deleteUser($id)
    {
        $user = $this->model->getById($id);

        // if ($this->isAuthenticated() && $user && $user['id'] === $_SESSION['user']) {
        return $this->model->delete($id);
        // }

        // return false;
    }

    public function userExists($data)
    {
        return isset($data['email']) && !empty($this->getUserByEmail($data['email'], ['email']));
    }

    // SESSION

    public function login($email, $password)
    {
        $user = $this->getUserByEmail($email);

        if ($this->verifyUser($user, $email, $password)) {
            // TODO: REPLACE SESSIONS WITH WEB TOKENS(JWT).
            // $_SESSION['user'] = $user['id'];
            // $_SESSION['authentified'] = true;

            return $user;
        }

        return false;
    }


    public function logout($refreshToken)
    {
        if ($this->isAuthenticated($refreshToken)) {
            // TODO: Revoke token
        }
    }

    private function verifyUser($user, $email, $password)
    {
        return $user &&
            $user['email'] === $email &&
            password_verify($password, $user['password']);
    }

    public function isAuthenticated($refresh_token)
    {
        return $this->token_manager->validateRefreshToken($refresh_token);
    }
}
