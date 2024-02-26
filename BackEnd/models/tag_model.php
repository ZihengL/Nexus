<?php

require_once "$path/models/base_model.php";

class TagsModel extends BaseModel
{

    protected $tableName = "tags";

    public function __construct($pdo)
    {
        parent::__construct($pdo, $this->tableName);
    }

    public function getByName($columnName, $name)
    {
        return parent::getAll($columnName, $name);
    }


    //Other Cruds
}
