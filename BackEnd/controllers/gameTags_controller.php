<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/gameTags_model.php";

class GameTagsController extends BaseController
{
    // protected $model;
    protected $tagId = "tagId";
    protected $gameID = "gameId";

    public function __construct($central_controller, $pdo)
    {
        $this->model = new GameTagsModel($pdo);
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
}
