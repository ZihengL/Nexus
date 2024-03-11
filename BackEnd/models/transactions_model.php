<?php
require_once "$path/models/base_model.php";

class TransactionsModel extends BaseModel
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, "transactions");
    }
}
