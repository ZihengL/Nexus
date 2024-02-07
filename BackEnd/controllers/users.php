<?php
require_once $path . '/models/user.php';

class UsersController
{
    private $model;
    private $token_manager;

    public function __construct($pdo, $token_manager)
    {
        $this->model = new UserModel($pdo);
        $this->token_manager = $token_manager;
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

    public function isAuthenticated($refresh_token)
    {
        return $this->token_manager->validateRefreshToken($refresh_token);
    }
}
