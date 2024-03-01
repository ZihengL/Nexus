<?php

require_once "$path/models/base_model.php";

class GameTagsModel extends BaseModel
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, "gamesTags");
    }


    //Other Cruds
}
