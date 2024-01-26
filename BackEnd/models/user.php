<?php

require_once "$path/models/base_model.php";

class UserModel extends BaseModel {
    public function __construct($pdo) {
        $table = "user";

        parent::__construct($pdo, $table);
    }

    public function userExists($email) {
        return !empty($this->getOne('email', $email, ['email']));
    }

    public function create($data) {
        if (!$this->validateData($data) || $this->userExists($data['email'])) {
            return false;
        }
       
        return parent::create($data);
    }

    public function formatData($data) {
        $keys = array_keys($data);

        // if (in_array('email', $keys)) {
        //     $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        // }
        if (in_array('password', $keys)) {
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