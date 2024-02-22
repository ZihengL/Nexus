<?php

require_once $path . '/models/base_model.php';

class TokenModel extends BaseModel
{
    public function __construct($pdo)
    {
        $table = "tokens";

        parent::__construct($pdo, $table, true);
    }

    public function isExpired($id)
    {
        $token = parent::getOne('id', $id, ['exp']);

        return $token && $token['exp'] < time();
    }

    public function isRevoked($id)
    {
        $token = parent::getOne('id', $id);

        return $token && $token['is_revoked'] === true;
    }

    public function deleteExpiredTokens()
    {
        $sql = "DELETE FROM $this->table WHERE exp < " . time();

        return parent::query($sql);
    }
}
