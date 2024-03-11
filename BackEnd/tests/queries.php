<?php

require_once "$path/models/base_model.php";

class TestQueries extends BaseModel
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, "reviews");

        // $this->test();
    }

    function test()
    {
        $title = 'doggo fights';

        $stmt = $this->pdo->prepare("
        SELECT 
            gamestags.*, 
            games.id AS game_id, 
            games.title AS game_title 
        FROM 
            gamestags 
        JOIN 
            games ON gamestags.gameId = games.id 
        WHERE 
            games.title = :title
        ");
        $stmt->bindParam(':title', $title);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            // Process each row
            echo "GameTag Info: " . print_r($row, true) . "\n";
        }
    }
}
