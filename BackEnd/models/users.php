<?php


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

require_once "$path/models/basemodel.php";


class UsersModel extends BaseModel {
    public function __construct($pdo) {
        $table = "user";

        parent::__construct($pdo, $table);
    }

    //Gets
    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }


    public function getByName($name) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetchAll();
    }

    public function getByLastname($lastname) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE lastname = ?");
        $stmt->execute([$lastname]);
        return $stmt->fetchAll();
    }

    public function getByPhoneNumber($phoneNumber) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE phone_number = ?");
        $stmt->execute([$phoneNumber]);
        return $stmt->fetch();
    }

    public function getByPrivilege($privilege) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE privilege = ?");
        $stmt->execute([$privilege]);
        return $stmt->fetchAll();
    }

    public function getByDescription($description) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE description = ?");
        $stmt->execute([$description]);
        return $stmt->fetchAll();
    }


    public function updateUser($id, $data) {
        $formattedData = $this->formatData($data);
        $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
        $formattedData['id'] = $id;

        $sql = "UPDATE $this->table SET $pairs WHERE id = ?";
        // print_r($sql);
        if ($this->query($sql, $formattedData)) {
            return true;
        } else {
            return false;
        }
    }

  
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    // other cruds
    public function create($data) {
        if (!$this->validateData($data) || $this->userExists($data['email'])) {
            return false;
        }
       
        return parent::create($data);
    }

    public function userExists($email) {
        return !empty($this->getOne('email', $email, ['email']));
    }

    public function formatData($data) {
        if (in_array('password', array_keys($data))) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return parent::formatData($data);
    }

    public function validateData($data) {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                return false;
            }
        }

        return true;
    }
} 