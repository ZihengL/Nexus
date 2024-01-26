<?php

require_once "$path/models/base_model.php";
require_once "$path/controllers/notifications.php";


// getById(id): [Notification]
// + getByRecipient(Id): [Notification]
// + getBySender(Id): [Notification]
// + getByType(type): [Notification]
// + getByStatus(bool): [Notification]
// + getByTimestamp(Date): [Notification]

class NotificationsModel extends BaseModel {
    public function __construct($pdo) {
        $table = "notifications";
        parent::__construct($pdo, $table);
    }

    public function isSubscribed($email) {
        return !empty($this->getOne('email', $email, ['email']));
    }


    // Gets
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }
    
    public function getByRecipient($recipientId){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE recipientId = ?");
        $stmt->execute([$recipientId]);

        return $stmt->fetch();
    }

    public function getBySender($senderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE senderId = ?");
        $stmt->execute([$senderId]);

        return $stmt->fetch();
    }
    
    public function getByType($type){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE type = ?");
        $stmt->execute([$type]);

        return $stmt->fetch();
    }

    
    public function getByStatus($isRead) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE isRead = ?");
        $stmt->execute([$isRead]);

        return $stmt->fetch();
    }
    
    public function getByDevs($devName){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE devName = ?");
        $stmt->execute([$devName]);

        return $stmt->fetch();
    }

    
    public function getPriceRange($filters) {
        $minPrice = intval($filters['minprice']);
        $maxPrice = intval($filters['maxprice']) === 0 ? 999999 : $filters['maxprice'];
    
        return "$minPrice, $maxPrice";
    }


    //Other Cruds

    public function addGame($game) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE game = ?");
        $stmt->execute([$game]);

        return $stmt->fetch();
    }
    
    public function updateGame($id,$Game){
        $formattedData = $this->formatData($Game);
        $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
        $formattedData['id'] = $id;

        $sql = "UPDATE $this->table SET $pairs WHERE id = ?";
        // print_r($sql);
        if ($this->query($sql, $formattedData)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteGame($id){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function filter($filters = [], $columns = []) {
        $priceRange = $this->getPriceRange($filters);
        unset($filters['minprice'], $filters['maxprice']);

        $filters = array_filter($filters, fn($filter) => !empty($filter));
        $mappedKeys = $this->implodeFiltersMap($filters);

        $sql =  "SELECT " . parseColumns($columns) . " FROM $this->table" .
                (!empty($mappedKeys) || !empty($priceRange) ? " WHERE " : "");

        if (!empty($mappedKeys)) {
            $sql .= $mappedKeys . (!empty($priceRange) ? " AND " : "");
        }
        $sql .= $priceRange;

        // TO DELETE
        // print($sql . "<br>");

        return $this->bindingQuery($sql, $filters);
    }

    public function implodeFiltersMap($filters) {
        $mappedKeys = array_map(fn($filter) => $filter . " = :$filter", array_keys($filters));

        return implode(' AND ', $mappedKeys);
    }


    

  
}