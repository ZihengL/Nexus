<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/user_model.php";

class UsersController extends BaseController
{
    private const RESTRICTED = ['password', 'email', 'phoneNumber', 'isAdmin'];

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
        $this->model = new UserModel($pdo);
        parent::__construct($central_controller);
    }

    private function restrictAccess($included_columns = [])
    {
        return array_filter($included_columns, function ($key) {
            return !in_array($key, self::RESTRICTED);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getOneMatchingColumn($column, $value, $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getOne($column, $value, $included_columns);
    }

    public function getAllUsers($included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll($included_columns);
    }

    public function getById($id, $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll($this->id, $id, $included_columns);
    }

    public function getByEmail($email, $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll($this->email, $email);
    }

    public function getByName($name, $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll($this->name, $name);
    }

    public function getByLastName($lastname, $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll($this->lastname, $lastname);
    }

    public function applyFiltersAndSorting($filters, $sorting, $included_columns)
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->applyFiltersAndSorting($filters, $sorting, $included_columns);
    }


    // ONLY FOR TESTING, DELETE IN FUTURE
    public function getAll_users($included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll_users($included_columns);
    }

    // OTHER CRUDS

    // Returns tokens to log the user in
    public function create($data)
    {
        if (!$this->userExists($data) && $this->model->create($data)) {
            $user = $this->model->getOne($this->email, $data[$this->email]);

            return $this->getTokensController()->generateTokenPair($user[$this->id]);
        }

        return false;
    }

    public function updateUser($id, $data, $password, $tokens)
    {
        $user = $this->model->getOne($this->id, $id);

        if ($user && $this->verifyUser($user[$this->email], $user, $password)) {
            if ($this->isAuthenticated($tokens)) {
                return $this->model->update($id, $data);
            }
        }

        return false;
    }

    public function deleteUser($id, $data, $password, $tokens)
    {
        $user = $this->model->getOne($this->id, $id);

        if ($user && $this->isAuthenticated($tokens)) {
            return $this->model->delete($id);
        }

        return false;
    }

    public function userExists($data)
    {
        return isset($data[$this->email]) &&
            !empty($this->model->getOne($this->email, $data[$this->email]));
    }

    //  ACCESS & SECURITY

    public function login($email, $password)
    {
        $user = $this->model->getOne($this->email, $email);

        if ($this->verifyUser($user, $email, $password)) {
            return $this->getTokensController()->generateTokenPair($user[$this->id]);
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
        return $this->getTokensController()->validateRefreshToken($tokens['refresh_token']);
    }
}
