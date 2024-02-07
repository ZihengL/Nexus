<?php
require_once $path . '/models/basemodel.php';

class RevokedTokenModel extends BaseModel
{
    public function __construct($pdo)
    {
        $table = "revoked_tokens";

        parent::__construct($pdo, $table);
    }

    public function create($data)
    {
        $data['revocation_date'] = time();

        return parent::create($data);
    }

    public function isExpired($id)
    {
        $token = parent::getById($id, ['expiry_date']);

        return $token && $token['expiry_date'] < time();
    }

    public function deleteExpiredTokens()
    {
        $sql = "DELETE FROM $this->table WHERE expiry_date < " . time();

        return parent::query($sql);
    }
}
