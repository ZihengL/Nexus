<?php

require_once "$path/models/basemodel.php";

class UserModel extends BaseModel
{

    // + Construct()
    // + getByEmail(String): User
    // + getByName(String): [User]
    // + getByLastname(String): [User]
    // + getByPhoneNumber(int): User
    // + getByPrivilege(Bool): [User]
    // + getByDescription(String): [User]
    // + createUser(data): Bool
    // + updateUser(int, data): Bool
    // + deleteUser(int): Bool

    protected $tableName = "user";
    protected $emailColumn = "email";
    protected $nameColumn = "name";
    protected $lastNameColumn = "lastname";
    protected $phoneNumberColumn = "phone_number";
    protected $privilegeColumn = "privilege";
    protected $descriptionColumn = "description";

    public function __construct($pdo)
    {
        $tableName = "user";

        parent::__construct($pdo, $tableName);
    }

    //Gets
    public function getByEmail($email)
    {
        return parent::getAll($this->emailColumn, $email);
    }

    public function getByName($name)
    {
        return parent::getAll($this->nameColumn, $name);
    }

    public function getByLastname($lastname)
    {
        return parent::getAll($this->lastNameColumn, $lastname);
    }

    public function getByPhoneNumber($phoneNumber)
    {
        return parent::getAll($this->phoneNumberColumn, $phoneNumber);
    }

    public function getByPrivilege($privilege)
    {
        return parent::getAll($this->privilegeColumn, $privilege);
    }

    public function getByDescription($description)
    {
        return parent::getAll($this->descriptionColumn, $description);
    }

    //other cruds

    public function updateUser($id, $data)
    {
        $formattedData = $this->formatData($data);
        $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
        $formattedData['id'] = $id;

        $sql = "UPDATE $this->tableName SET $pairs WHERE id = ?";
        // print_r($sql);
        if ($this->query($sql, $formattedData)) {
            return true;
        } else {
            return false;
        }
    }


    public function deleteUser($id)
    {
        return parent::delete($id);
    }

    public function create($data)
    {
        if (!$this->validateData($data) || $this->userExists($data['email'])) {
            return false;
        }


        return parent::create($data);
    }

    public function userExists($email)
    {
        return !empty($this->getOne('email', $email, ['email']));
    }

    public function formatData($data)
    {
        if (in_array('password', array_keys($data))) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }


        return parent::formatData($data);
    }

    public function validateData($data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                return false;
            }
        }

        return true;
    }
}
