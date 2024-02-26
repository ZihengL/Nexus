<?php

require_once "$path/models/base_model.php";
// require_once "$path/controllers/tagscontroller.php";

class GameTagsModel extends BaseModel
{

    protected $tableName = "gamesTags";

    public function __construct($pdo)
    {
        parent::__construct($pdo, $this->tableName);
    }


    //Other Cruds
}




