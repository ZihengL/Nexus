<?php
require_once $path . '/models/basemodel.php';

class RevokedTokenModel extends BaseModel
{
    public function __construct($pdo)
    {
        $table = "revoked_tokens";

        parent::__construct($pdo, $table, true);
    }

    public function isExpired($id)
    {
        $token = parent::getById($id, ['exp']);

        return $token && $token['exp'] < time();
    }

    public function deleteExpiredTokens()
    {
        $sql = "DELETE FROM $this->table WHERE exp < " . 5000;

        return parent::query($sql);
    }
}
