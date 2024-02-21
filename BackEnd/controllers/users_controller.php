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

    public function getAll_users($included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll($included_columns);
    }

    public function userExists($data)
    {
        return isset($data[$this->email]) &&
            !empty($this->model->getOne($this->email, $data[$this->email]));
    }

    // OTHER CRUDS

    // Returns tokens to log the user in
    public function create($data)
    {
        if (!$this->userExists($data) && $this->model->create($data)) {
            $user = $this->model->getOne($this->email, $data[$this->email]);
            // echo "users_controller: " .$user . "<br>";

            return $this->login($data[$this->email], $data[$this->password]);
        }

        return false;
    }

    public function update($id, $data, $tokens = null)
    {
        if ($this->authenticate($id, $tokens)) {
            return $this->model->update($id, $data);
        }

        return false;
    }

    public function delete($id, $tokens = null)
    {
        if ($this->authenticate($id, $tokens)) {
            return $this->model->delete($id);
        }

        return false;
    }

    //  ACCESS & SECURITY

    public function login($email, $password)
    {
        $user = $this->model->getOne($this->email, $email);

        if ($this->verifyUser($user, $email, $password)) {
            // $tokens = $this->getTokensController()->generateTokenPair($user[$this->id]);
            // echo "<br> login data : <br>";
            // return $tokens;
            return $this->getTokensController()->generateTokenPair($user[$this->id]);
        }

        return false;
    }

    public function logout($tokens)
    {
        return $this->getTokensController()->revokeTokens($tokens);
    }

    public function authenticate($id, $tokens)
    {
        return $this->getTokensController()->validateTokens($id, $tokens);
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
