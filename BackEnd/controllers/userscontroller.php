<?php

// require_once "$path/controllers/base_controller.php";
require_once $path . '/models/usermodel.php';
require_once $path . '/controllers/webtokens.php';

class UsersController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new UserModel($pdo);
    }

    public function getUserById($id, $columns = [])
    {
        return $this->model->getById($id, $columns);
    }

    public function getUserByEmail($email, $columns = [])
    {
        return $this->model->getOne('email', $email, $columns);
    }

    public function getUsersByName($name, $columns = [])
    {
        return $this->model->get('name', $name, $columns);
    }

    public function getUsersByLastName($lastname, $columns = [])
    {
        return $this->model->get('lastname', $lastname, $columns);
    }

    // ONLY FOR TESTING, DELETE IN FUTURE
    public function getAllUsers($columns = [])
    {
        return $this->model->getAll();
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

    public function isAuthenticated($refreshToken)
    {
        return WebTokens::validateRefreshToken($refreshToken);
    }

    // public function isAuthenticated($token)
    // {
    //     if (session_status() !== PHP_SESSION_ACTIVE) {
    //         session_start();
    //     }

    //     return  isset($_SESSION['user']) &&
    //             isset($_SESSION['authentified']) && 
    //             $_SESSION['authentified'];
    // }
}
