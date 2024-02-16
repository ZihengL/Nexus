<?php

require_once "$path/models/base_model.php";

class ReviewModel extends BaseModel {
    
    protected $tableName = "reviews";


    public function __construct($pdo) {
        parent::__construct($pdo, $this->tableName);
    }
    

    public function getByGameId($columnName, $gameId) {
        return parent::getAll($columnName, $gameId);
    }


    public function getByUserId($columnName, $userId) {
        return parent::getAll($columnName, $userId);
    }


    public function getByrating($columnName, $userId) {
        return parent::getAll($columnName, $userId);
    }


    public function getByComment($columnName, $comment) {
        return parent::getAll($columnName, $comment);
    }

    public function getBytimestampt($columnName, $timestamp) {
        return parent::getAll($columnName, $timestamp);
    }

  
    public function deleteReview($id) {
        return parent::delete($id);
    }


    public function applyFiltersAndSorting($filters,  $sorting = null) {
        return parent::applyFiltersAndSorting($filters, $sorting);
    }
    
  
    public function getAll_reviews() {
        return parent::getAll();
    }
}