<?php

require_once "$path/models/base_model.php";

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

    public function __construct($pdo)
    {
        $tableName = "user";

        parent::__construct($pdo, $tableName);
    }

    //Gets
    public function getByEmail($email, $columnName)
    {
        return parent::getAll($columnName, $email, parent::getColumns(true));
    }

    public function getByName($name, $columnName)
    {
        return parent::getAll($columnName, $name, parent::getColumns(true));
    }

    public function getByLastname($columnName, $lastname)
    {
        return parent::getAll($columnName, $lastname, parent::getColumns(true));
    }

    public function getByPhoneNumber($columnName, $phoneNumber)
    {
        return parent::getAll($columnName, $phoneNumber, parent::getColumns(true));
    }

    public function getByPrivilege($columnName, $privilege)
    {
        return parent::getAll($columnName, $privilege, parent::getColumns(true));
    }

    public function getByDescription($columnName, $description)
    {
        return parent::getAll($columnName, $description, parent::getColumns(true));
    }

    public function getAll_users()
    {
        return parent::getAll();
    }

    //other cruds

    public function applyFiltersAndSorting($filters, $sorting, $includedColumns)
    {
        return parent::applyFiltersAndSorting($filters, $sorting, $includedColumns);
    }

    // public function update($id, $data)
    // {
    //     $formattedData = $this->formatData($data);
    //     $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
    //     $formattedData['id'] = $id;

    //     $sql = "UPDATE $this->table SET $pairs WHERE id = ?";

    //     if ($this->query($sql, $formattedData)) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

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
