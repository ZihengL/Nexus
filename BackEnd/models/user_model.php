<?php

require_once "$path/models/base_model.php";

class UserModel extends BaseModel
{

    public function __construct($pdo)
    {
        $tableName = "user";

        parent::__construct($pdo, $tableName);
    }

    //Gets

    //other cruds

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

    public function create($data)
    {
        // echo "<br> create user_model <br>";
        // print_r($data);
        if (!$this->validateData($data) || $this->userExists($data['email'])) {
            return false;
        }
        $new_data = $this->formDataProperly($data);
        return parent::create($new_data);
    }

    public function userExists($email)
    {
        return !empty($this->getOne('email', $email, ['email']));
    }


    public function formDataProperly($data)
    {
        error_log(print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true));
        $formattedData = [];
        foreach ($this->columns as $column) {
            if ($column == 'id') {
                continue;
            }

            if (in_array($column, ['email', 'password'])) {
                // Process 'email' and 'password' if present in data
                if (array_key_exists($column, $data)) {
                    $formattedData[$column] = $data[$column];
                }
            } elseif ($column == 'creationDate') {
                $formattedData[$column] = date('Y-m-d');
            } else {
                $formattedData[$column] = null;
            }
        }

        // echo "Plaintext Password2 (for debugging only): " . $formattedData['password'] . "<br>";

        if (array_key_exists('password', $formattedData)) {

            // echo "Plaintext Password3 (for debugging only): " . $formattedData['password'] . "<br>";

            $formattedData['password'] = password_hash($formattedData['password'], PASSWORD_DEFAULT);
        }
        return $formattedData;
    }
    // public function formatData($data)
    // {
    //     if (in_array('password', array_keys($data))) {
    //         $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    //     }

    //     return parent::formatData($data);
    // }

    public function validateData($data)
    {
        if (isset($data['email']) && isset($data['password']) && !empty($data['email']) && !empty($data['password'])) {
            return true;
        } else {
            return false;
        }
    }

    // public function validateData($data)
    // {
    //     foreach ($data as $key => $value) {
    //         if (empty($value)) {
    //             return false;
    //         }
    //     }

    //     return true;
    // }
}
