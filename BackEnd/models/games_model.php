<?php
require_once "$path/models/base_model.php";

class GamesModel extends BaseModel
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, "games");
    }

    //Other Cruds

    public function create($game)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE game = ?");
        $stmt->execute([$game]);

        return $stmt->fetch();
    }

    public function update($id, $Game)
    {
        $formattedData = $this->formatData($Game);
        $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
        $formattedData['id'] = $id;

        $sql = "UPDATE $this->table SET $pairs WHERE id = ?";

        if ($this->query($sql, $formattedData)) {
            return true;
        } else {
            return false;
        }
    }

    //Updates
    function updateGameTags($pdo, $gameId, array $newTagIds)
    {
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

    //Getters

    // public function getOne($column = null, $value = null, $included_columns = [], $join_keys = [])
    // {
    //     if (in_array('tags', $included_columns)) {
    //         $key = array_search('tags', $included_columns);
    //         if ($key !== false) {
    //             unset($included_columns[$key]);
    //         }
    //         $results = $this->joinGamesAndTags($column, $value, $included_columns);
    //         return $this->appendTagsToGames($results);
    //     }
    //     $results = $this->joinGamesAndTags($column, $value, $included_columns);
    //     return $this->appendTagsToGames($results);
    // }

    // public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $join_keys = [])
    // {
    //     if (in_array('tags', $included_columns)) {
    //         $key = array_search('tags', $included_columns);
    //         if ($key !== false) {
    //             unset($included_columns[$key]);
    //         }
    //         $results = $this->joinGamesAndTags($column, $value, $included_columns, $sorting);
    //         return $this->appendTagsToGames($results);
    //     }
    //     $results = $this->joinGamesAndTags($column, $value, $included_columns, $sorting);
    //     return $this->appendTagsToGames($results);
    // }

    //Tools
    public function appendTagsToGames($results)
    {
        // Organize games and their tags
        $games = [];
        foreach ($results as $row) {
            $gameId = $row['g.id'] ?? $row['id']; // Adjust based on actual key present
            if (!isset($games[$gameId])) {
                $games[$gameId] = [
                    'id' => $gameId,
                    'tags' => []
                ];
                // Add dynamic columns to game data, excluding tagId and tagName
                foreach ($row as $key => $value) {
                    if (!in_array($key, ['tagId', 'tagName', 'g.id'])) {
                        $games[$gameId][$key] = $value;
                    }
                }
            }

            // Append tag to the game if tagId is not null
            if (!is_null($row['tagId'])) {
                $games[$gameId]['tags'][] = [
                    'id' => $row['tagId'],
                    'name' => $row['tagName']
                ];
            }
        }

        // Return array values to reset indices
        return array_values($games);
    }

    public function formatIncludedColumns($included_columns = [])
    {
        //  echo "<br>  includedColumns : " . print_r($included_columns, true);
        // Check if 'id' is present in the array
        $hasIdWithoutAlias = in_array('id', $included_columns, true);
        // Check if 'g.id' is present in the array
        $hasIdWithAlias = in_array('g.id', $included_columns, true);

        if ($hasIdWithoutAlias) {
            // Replace 'id' with 'g.id' if present
            $included_columns = array_map(function ($column) {
                return $column === 'id' ? 'g.id' : $column;
            }, $included_columns);
        }

        // If the array isn't empty and 'g.id' wasn't explicitly added, add it before prefixing the rest
        if (!empty($included_columns) && !$hasIdWithAlias) {
            array_unshift($included_columns, 'g.id'); // Ensure 'g.id' is added
        }

        // Prefix other columns with 'g.' where necessary, skipping 'g.id' as it's already correctly formatted
        $included_columns = array_map(function ($column) {
            // No need to check for 'g.id' again as it was already handled in the previous steps
            return strpos($column, '.') === false && $column !== 'g.id' ? "g." . $column : $column;
        }, $included_columns);

        if (empty($included_columns)) {
            // Fetch all columns from the table if none are specified
            $included_columns = $this->getColumns(true);
            // Prefix each column with 'g.' and ensure 'g.id' is included
            $included_columns = array_map(function ($column) {
                return "g." . $column;
            }, $included_columns);
        }

        // Ensure the array is unique to avoid duplicate column names
        return array_unique($included_columns);
    }

    public function joinGamesAndTags($column = null, $value = null, $included_columns = [], $sorting = [])
    {

        $columnsToParse = $this->formatIncludedColumns($included_columns);
        // echo "<br>  columnsToParse : " . print_r($columnsToParse, true);

        // Start building the SQL query with dynamic column selection
        $selectColumns = $this->parseColumns($columnsToParse);
        $sql = "SELECT " . $selectColumns . ", t.id AS tagId, t.name AS tagName
                FROM {$this->table} g
                LEFT JOIN gamesTags gt ON g.id = gt.gameId
                LEFT JOIN tags t ON gt.tagId = t.id";

        $params = [];
        if ($column && $value) {
            $whereColumn = strpos($column, '.') === false ? "g.$column" : $column; // Prefix column with 'g.' if not aliased
            $sql .= " WHERE $whereColumn = ?";
            $params[] = $value;
        }

        // Apply sorting, if provided
        $sortingSql = $this->applySorting($sorting);
        if (!empty($sortingSql)) {
            $sql .= " ORDER BY " . $sortingSql;
        }

        // Execute the query and fetch results
        $stmt = $this->query($sql, $params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }



    public function joinTagsAndGetAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        // $sql = 'SELECT * FROM ' . $this->table . ' WHERE 1 = 1';
        $columnsToParse = $this->formatIncludedColumns($included_columns);
        // echo "<br>  columnsToParse : " . print_r($columnsToParse, true);

        // Start building the SQL query with dynamic column selection
        $selectColumns = $this->parseColumns($columnsToParse);
        $sql = "SELECT " . $selectColumns . ", t.id AS tagId, t.name AS tagName
                FROM {$this->table} g
                LEFT JOIN gamesTags gt ON g.id = gt.gameId
                LEFT JOIN tags t ON gt.tagId = t.id";


        $filterResults = $this->applyFilters($filters);
        $sortingResults = $this->applySorting($sorting);

        // $sqlWithFilters = $filterResults['sql'];
        $sqlWithFilters = ltrim($filterResults['sql'], ' AND');
        $params = $filterResults['params'];
        // echo "<br>  params : " . print_r($params, true);

        $sqlWithFiltersAndSorting = $sortingResults ? $sqlWithFilters . ' ORDER BY ' . $sortingResults : $sqlWithFilters;
        $sqlWithFiltersAndSorting = $sql . " WHERE " . $sqlWithFiltersAndSorting;

        // echo "<br>Final SQL: " . $sqlWithFiltersAndSorting;

        $results = $this->bindingQuery($sqlWithFiltersAndSorting, $params);
        return $this->appendTagsToGames($results);
    }
}
