<?php

require_once "$path/models/base_model.php";
// require_once "$path/controllers/tagscontroller.php";

class TagsModel extends BaseModel {

    protected $tableName = "tags";

    public function __construct($pdo) {
        parent::__construct($pdo, $this->tableName);
    }
    
    public function getByName($columnName, $name){
        return parent::getAll($columnName, $name);
    }

    public function getAll() {
        return parent::getAll();
    }

    //Other Cruds
    public function applyFiltersAndSorting( $filters , $sorting){
        return parent::applyFiltersAndSorting($filters , $sorting);
    }

    public function delete($id){
        return parent::delete($id);
    }

    // public function update($id, $gameId, $tagId){
    //     $formattedData = $this->formatData($Game);
    //     $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
    //     $formattedData['id'] = $id;

    //     $sql = "UPDATE $this->table SET $pairs WHERE id = ?";
    //     // print_r($sql);
    //     if ($this->query($sql, $formattedData)) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    // public function updateRelationTable($id, $gameId, $tagId){
    //     $formattedData = $this->formatData($Game);
    //     $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
    //     $formattedData['id'] = $id;

    //     $sql = "UPDATE $this->table SET $pairs WHERE id = ?";
    //     // print_r($sql);
    //     if ($this->query($sql, $formattedData)) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

 
    
}