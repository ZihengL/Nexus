<?php

class UsersDownloadsModel extends BaseModel
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, "users_downloads");
    }
}
