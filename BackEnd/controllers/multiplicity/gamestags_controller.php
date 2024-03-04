<?php
require_once "$path/controllers/base_controller.php";
require_once "$path/models/multiplicity/gamestags_model.php";

class GamestagsController extends BaseController
{
    protected $tagId = 'tagId';
    protected $gameID = 'gameId';

    public function __construct($central_controller, $pdo)
    {
        $this->model = new GamestagsModel($pdo);
        parent::__construct($central_controller);
    }

    public function create($data, $jwts = null)
    {
        $tagName = $data["name"];
        $tagWasAdded = $this->getTagsController()->getOne('name', $tagName);

        if ($tagWasAdded) {
            if ($this->model->create($data)) {
                return $this->createResponse(true, "Tag {$tagName} was created successfully. Updated game_Tags table as needed.");
            }
            return $this->createResponse(false, 'Failed to create game_tags new relation');
        }
        return $this->createResponse(false, 'Failed to create tag');
    }

    public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    {
        //TODO: FULL JOIN BY DEFAULT ON BOTH ENDS

        return parent::getOne($column, $value, $included_columns, $joined_tables);
    }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        //TODO: FULL JOIN BY DEFAULT ON BOTH ENDS

        return parent::getAll($column, $value, $included_columns, $sorting, $joined_tables);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        //TODO: FULL JOIN BY DEFAULT ON BOTH ENDS

        return parent::getAllMatching($filters, $sorting, $included_columns, $joined_tables);
    }

    public function getRelated() {
        
    }
}
