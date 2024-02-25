<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/game_model.php";

class TagsController extends BaseController
{

    // protected $model;
    protected $id = "id";
    protected $name = "name";


    public function __construct($central_controller, $pdo)
    {
        $this->model = new TagsModel($pdo);
        parent::__construct($central_controller);
    }

    // GETTERS

    public function getById($id)
    {
        return $this->model->getOne($this->id, $id);
    }

    public function getByName($name)
    {
        return $this->model->getByName($this->name, $name);
    }

    // Other CRUDs 



    //REBECCA





    public function create($data)
    {
        $isValid = $this->validateData("create", $data);
        $name = $data["name"];
        $gameId = $data["gameId"];

        if ($isValid['isSuccessful']) {

            $tagExists = $this->getOne("name", $name);

            if ($tagExists) {
                $newData = ['gameId' => $gameId, 'tagId' => $tagExists["id"]];
            } else {
                if ($this->model->create(['name' => $name])) {
                    $createdTag = $this->getOne("name", $name);
                    $newData = ['gameId' => $gameId, 'tagId' => $createdTag["id"]];
                } else {
                    return $this->createResponse(false, 'Failed to create new tag');
                }
            }
            return $this->getGameTagsController()->create($newData);
        }
        return $isValid;
    }


    public function delete($data)
    {
        $isValid = $this->validateData("delete", $data);
        $name = $data["name"];
        $gameId = $data["gameId"];

        if ($isValid['isSuccessful']) {

            $tagExists = $this->getOne("name", $name);

            if ($tagExists) {
                $tagId = $tagExists["id"];
                $filters = [
                    'gameId' => $gameId,
                    'tagId' => $tagId,
                ];
                $gameTagsRelation = $this->getGamesController()->getAllMatching($filters);

                if ($gameTagsRelation && $this->model->delete($tagId)) {
                    if ($this->getGameTagsController()->delete($gameTagsRelation["id"])) {
                        return $this->createResponse(true, 'tags successfully deleted in gameTags and tags table');
                    }
                }

            }
        }
        return $isValid;
    }



    public function update($id, $data)
    {
        $isValid = $this->validateData("update", $data);
        $newName = $data["newName"];

        if ($isValid['isSuccessful']) {

            $similarTagExist = $this->getOne("name", $newName);
            if ($similarTagExist) {
                return $this->createResponse(false, 'there is already a tag with that same name');
            }
            $newData = ['id' => $id, 'name' => $newName];
            if ($this->model->update($id, $newData)) {
                return $this->createResponse(false, 'tag has been updated');
            }

        }
        return $this->$isValid;
    }



    public function validateData($action, $data)
    {
        $name = $data["name"] ?? null;
        $gameId = $data["gameId"] ?? null;
        $oldName = $data["oldName"] ?? null;
        $newName = $data["newName"] ?? null;

        $gameExists = $this->getGamesController()->getOne('id', $gameId);

        $isEmpty_forCreateDelete = empty($name) || empty($gameId);
        $isEmpty_forUpdate = empty($oldName) || empty($newName) || empty($gameId);


        if (!$gameExists) {
            return $this->createResponse(false, 'Game does not exist');
        }
        switch ($action) {
            case "create":
            case "delete":
                if ($isEmpty_forCreateDelete) {
                    return $this->createResponse(false, 'Missing info to create or delete tag');
                }
                return $this->createResponse(true, 'isValid');

            case "update":
                if ($isEmpty_forUpdate || $this->getOne("name", $oldName)) {
                    return $this->createResponse(false, 'Missing info to update tag or tag does not exist');
                }
                return $this->createResponse(true, 'isValid');

            default:
                return $this->createResponse(false, 'error in tags_controller');
        }

    }




}




