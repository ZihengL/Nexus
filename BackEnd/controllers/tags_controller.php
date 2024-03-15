<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/game_model.php";
require_once "$path/models/tag_model.php";

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

    public function create($data, $jwts = null)
    {
        $isValid = $this->validateData("create", $data);
        $name = $data["name"];
        $gameId = $data["gameId"];
        $isValidArray = json_decode($isValid, true);

        if ($isValidArray['isSuccessful']) {

            $tagExists = $this->getOne("name", $name);

            if ($tagExists) {

                $tagId = $tagExists["id"];
                $filters = [
                    'gameId' => $gameId,
                    'tagId' =>  $tagExists["id"],
                ];

                $gameTagsRelation = $this->getGameTagsController()->getAllMatching($filters);

                if($gameTagsRelation){
                    return $this->createResponse(true, "Tag {$name} and game_Tags already exist.");
                }else{
                    $newData = ['gameId' => $gameId, 'tagId' => $tagExists["id"], 'name' => $name];
                }
              
            } else {
                if ($this->model->create(['name' => $name])) {
                    $createdTag = $this->getOne("name", $name);
                    $newData = ['gameId' => $gameId, 'tagId' => $createdTag["id"], 'name' => $name];
                } else {
                    return $this->createResponse(false, 'Failed to create new tag');
                }
            }
            return $this->getGameTagsController()->create($newData);
        }
        return $isValidArray['isSuccessful'];
    }


    public function delete($data, $jwts = null)
    {
        $isValid = $this->validateData("delete", $data);
        $name = $data["name"];
        $gameId = $data["gameId"];
        $isValidArray = json_decode($isValid, true);

        if ($isValidArray['isSuccessful']) {

            $tagExists = $this->getOne("name", $name);

            if ($tagExists) {
                $tagId = $tagExists["id"];
                $filters = [
                    'gameId' => $gameId,
                    'tagId' => $tagId,
                ];
                $gameTagsRelation = $this->getGameTagsController()->getAllMatching($filters);

                if (!empty($gameTagsRelation) && $this->model->delete($tagId)) {
                    // echo "gameTagsRelation ". print_r($gameTagsRelation, true). "<br>";
                    if ($this->getGameTagsController()->delete($gameTagsRelation[0]["id"])) {
                        return $this->createResponse(true, 'tags successfully deleted in gameTags and tags table');
                    }
                }
                // $this->createResponse(false, 'tag deletion unsuccessful');
                return false;
            }
            // $this->createResponse(false, 'Cannot delete tag that doesnt exist');
            return false;
        }
        return $isValidArray['isSuccessful'];
    }



    public function update($id, $data, $jwts = null)
{
    $isValid = $this->validateData("update", $data);
    $isValidArray = json_decode($isValid, true); 

    if ($isValidArray['isSuccessful']) {
        $newName = $data["newName"];
        $similarTagExist = $this->getOne("name", $newName);
        if ($similarTagExist) {
            return $this->createResponse(false, 'There is already a tag with that same name');
        }
        $newData = ['id' => $id, 'name' => $newName];
        if ($this->model->update($id, $newData)) {
            return $this->createResponse(true, 'Tag has been updated');
        } else {
            return $this->createResponse(false, 'Failed to update tag');
        }
    } else {
        return $isValidArray['isSuccessful'];
    }
}



    public function validateData($action, $data)
    {
        $id = $data["id"] ?? null;
        $name = $data["name"] ?? null;
        $gameId = $data["gameId"] ?? null;
        $oldName = $data["oldName"] ?? null;
        $newName = $data["newName"] ?? null;

        $gameExists = $this->getGamesController()->getOne('id', $gameId);

        $isEmpty_forCreateDelete = empty($name) || empty($gameId);
        $isEmpty_forUpdate =empty($id) || empty($oldName) || empty($newName) || empty($gameId);

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
                if ($isEmpty_forUpdate || !$this->getOne("name", $oldName)) {
                    return $this->createResponse(false, 'Missing info to update tag or tag does not exist');
                }
                return $this->createResponse(true, 'isValid');

            default:
                return $this->createResponse(false, 'error in tags_controller');
        }

    }




}




