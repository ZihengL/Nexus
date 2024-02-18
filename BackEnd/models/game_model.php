<?php
require_once "$path/models/base_model.php";

class GameModel extends BaseModel
{
    protected $tableName = "games";


    public function __construct($pdo)
    {
        parent::__construct($pdo, $this->tableName);
    }

    public function getByColumn($column, $value)
    {
        return parent::getAll($column, $value);
    }


    public function getAll_games($sorting)
    {
        return parent::getAll($column = null, $value = null, $columns = [], $sorting );
    }

    public function getMinMaxPrice()
    {
        $sql = "SELECT MIN(price) AS minPrice, MAX(price) AS maxPrice FROM $this->tableName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                'minPrice' => $result['minPrice'],
                'maxPrice' => $result['maxPrice']
            ];
        } else {
            return [
                'minPrice' => null,
                'maxPrice' => null
            ];
        }
    }


    //Other Cruds

    public function applyFiltersAndSorting($filters, $sorting, $includedColumns)
    {
        return parent::applyFiltersAndSorting($filters, $sorting, $includedColumns);
    }

    public function addGame($game)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE game = ?");
        $stmt->execute([$game]);

        return $stmt->fetch();
    }

    public function updateGame($id, $Game)
    {
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

    public function deleteGame($id)
    {
        return parent::delete($id);
        // $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
        // $stmt->execute([$id]);

        // return $stmt->fetch();
    }



    //WORKING ON IT
    function updateGameTags($pdo, $gameId, array $newTagIds) {
        // Begin a transaction
        $pdo->beginTransaction();
        
        try {
            // Remove existing tags for the game
            $stmt = $pdo->prepare('DELETE FROM game_tags WHERE game_id = :game_id');
            $stmt->execute([':game_id' => $gameId]);
    
            // Insert new tags
            $sql = 'INSERT INTO game_tags (game_id, tag_id) VALUES (:game_id, :tag_id)';
            $stmt = $pdo->prepare($sql);
            foreach ($newTagIds as $tagId) {
                $stmt->execute([':game_id' => $gameId, ':tag_id' => $tagId]);
            }
    
            // Commit the transaction
            $pdo->commit();
        } catch (Exception $e) {
            // Rollback if there's an error
            $pdo->rollBack();
            throw $e;
        }
    }



}
