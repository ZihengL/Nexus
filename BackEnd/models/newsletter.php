<?php

require_once "$path/models/basemodel.php";

class NewsletterModel extends BaseModel {
    public function __construct($pdo) {
        $table = "newsletter";
        parent::__construct($pdo, $table);
    }

    public function isSubscribed($email) {
        return !empty($this->getOne('email', $email, ['email']));
    }

    // public function getSubById($id) {
    //     return $this->get("id", $id);
    // }

    // public function getSubByEmail($email) {
    //     return $this->get($this->fields[0], $email);
    // }

    // public function getSubByName($name) {
    //     return $this->get($this->fields[1], $name);
    // }

    // public function getSubByLastName($lastname) {
    //     return $this->get($this->fields[2], $lastname);
    // }
}