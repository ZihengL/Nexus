<?php

require_once "$path/models/base_model.php";

class ReviewModel extends BaseModel
{

    protected $tableName = "reviews";


    public function __construct($pdo)
    {
        parent::__construct($pdo, $this->tableName);
    }


}
