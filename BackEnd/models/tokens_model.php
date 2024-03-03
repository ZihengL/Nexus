<?php
require_once "$path/models/base_model.php";

class TokensModel extends BaseModel
{
    public function __construct($pdo)
    {
        $table = "tokens";

        parent::__construct($pdo, "tokens");
    }

    public function isExpired($id)
    {
        $token = parent::getOne('id', $id, ['exp']);

        return $token && $token['exp'] < time();
    }

    public function deleteExpiredTokens()
    {
        $sql = "DELETE FROM $this->table WHERE exp < " . time();

        return parent::query($sql);
    }
}
