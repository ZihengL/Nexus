<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/user_model.php";

class UsersController extends BaseController
{
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
        $this->restricted_columns = ['password', 'email', 'phoneNumber', 'isAdmin'];
        parent::__construct($central_controller);
    }

    protected function restrictAccess($included_columns = [])
    {
        if (!is_array($included_columns)) {
            $included_columns = [];
        }

        return array_filter($included_columns, function ($key) {
            return !in_array($key, $this->restricted_columns);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getOne($column, $value, $included_columns = [])
    {

        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getOne($column, $value, $included_columns);
    }

    // public function getAllUsers($included_columns = [])
    // {
    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getAll($included_columns);
    // }

    // public function getById($id, $included_columns = [])
    // {
    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getAll($this->id, $id, $included_columns);
    // }

    // public function getByEmail($email, $included_columns = [])
    // {
    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getAll($this->email, $email);
    // }

    // public function getByName($name, $included_columns = [])
    // {
    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getAll($this->name, $name);
    // }

    // public function getByLastName($lastname, $included_columns = [])
    // {
    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getAll($this->lastname, $lastname);
    // }

    // public function getAll_users($included_columns = [])
    // {
    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getAll($included_columns);
    // }

    public function userExists($data)
    {
        return isset($data[$this->email]) &&
            !empty($this->model->getOne($this->email, $data[$this->email]));
    }

    // OTHER CRUDS

    // Returns tokens to log the user in
    // TODO: CREATE USER FOLDER
    public function create($data)
    {
        if (!$this->userExists($data) && $this->model->create($data)) {
            $user = $this->model->getOne($this->email, $data[$this->email]);
            // echo "users_controller: " .$user . "<br>";

            // return $this->login($data[$this->email], $data[$this->password]);
        }

        return null;
    }

    public function update($id, $data, $jwts = null)
    {
        if ($this->authenticate($id, $jwts)) {
            return $this->model->update($id, $data);
        }

        return null;
    }

    public function delete($id, $jwts = null)
    {
        if ($this->authenticate($id, $jwts)) {
            return $this->model->delete($id) &&
                $this->getTokensController()->deleteAllFromUser($id, $jwts);
        }

        return false;
    }

    //  ACCESS & SECURITY

    // Do this if user has valid tokens stored
    public function authenticate($id, $jwts)
    {
        return $this->getTokensController()->validateTokens($id, $jwts);
    }

    // Do this if user needs to do a fresh login
    public function login($email, $password)
    {

        $user = $this->model->getOne($this->email, $email);
        echo "login user: " . print_r($user, true) . "<br>";

        return $this->getTokensController()->generateTokensOnValidation($user, $email, $password);
    }

    public function logout($id, $jwts)
    {
        $tokens_controller = $this->getTokensController();

        if ($tokens_controller->validateTokens($id, $jwts)) {
            return $this->getTokensController()->delete($id);
        }

        return false;
    }

    private function verifyUser($user, $email, $password)
    {
        // echo "password: " .$password . "<br>";
        // // echo "user[$this->password]: " .$user[$this->password] . "<br>";
        // $isVerifiedPwd = password_verify($password, $hashed);
        // echo "isVerifiedPwd: " . $isVerifiedPwd . "<br>";
        return $user[$this->email] === $email && password_verify($password, $user[$this->password]);
    }
}
