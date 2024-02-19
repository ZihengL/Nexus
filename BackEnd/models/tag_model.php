<?php

require_once "$path/models/base_model.php";
// require_once "$path/controllers/tagscontroller.php";

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

    public function getAll_tags()
    {
        return parent::getAll();
    }

    //Other Cruds
    public function getAllMatching($filters, $sorting = null)
    {
        return parent::getAllMatching($filters, $sorting);
    }

    public function delete($id)
    {
        return parent::delete($id);
    }
}
