<?php

require_once "$path/models/basemodel.php";

class FriendsModel extends BaseModel {
    
    public function __construct($pdo) {
        $table = "friends";

        parent::__construct($pdo, $table);
    }

    
}