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
        parent::__construct($central_controller);
    }

    // public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    // {
    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getAllMatching($filters, $sorting, $included_columns);
    // }

    // public function getOne($column, $value, $included_columns = [])
    // {

    //     $included_columns = $this->restrictAccess($included_columns);

    //     return $this->model->getOne($column, $value, $included_columns);
    // }

    public function userExists($data)
    {
        return isset($data[$this->email]) &&
            !empty($this->model->getOne($this->email, $data[$this->email]));
    }

    private function getByEmail($email)
    {
        return $this->model->getOne($this->email, $email);
    }

    // OTHER CRUDS

    // Returns tokens to log the user in
    // TODO: CREATE USER FOLDER
    public function create($data, $jwts = null)
    {
        if ($this->model->create($data)) {
            $user = $this->getOne($this->email, $data[$this->email]);

            if ($user) {
                // $drive_id = $this->getGoogleClientManager()->createUserFolder($user);
                // $user[$this->drive_id] = $drive_id;
                // echo "register";
                // $this->model->update($user[$this->id], $user);
                return true;
            }
        }

        return false;
    }

    public function update($id, $data, $jwts = null)
    {
        $jwts = $data["tokens"];
        if ($jwts = $this->authenticate($id, $jwts)) {
            $this->model->update($id, $data);

            return $jwts;
        }

        return false;
    }

    public function delete($data, $jwts = null)
    {
        $jwts = $data["tokens"];
        $id = $data["id"];
        if ($jwts = $this->authenticate($id, $jwts)) {
            $this->model->delete($id);
            $this->getTokensController()->deleteAllFromUser($id, $jwts);

            return $jwts;
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
        // echo "login email: " . $email . "<br>";
        $user = $this->model->getOne($this->email, $email);
        // echo "login user: " . print_r($user, true) . "<br>";
        if(!$user){
            return false;
        }
        return $this->getTokensController()->generateTokensOnValidation($user, $email, $password);
    }

    public function logout($id, $jwts)
    {
        $tokens_controller = $this->getTokensController();

        if ($tokens_controller->validateTokens($id, $jwts)) {
            return $this->getTokensController()->delete($jwts["refresh_token"]);
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
