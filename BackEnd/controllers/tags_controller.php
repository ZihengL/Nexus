<?php
require_once "$path/controllers/base_controller.php";
require_once "$path/models/games_model.php";
require_once "$path/models/tags_model.php";

class TagsController extends BaseController
{
    protected $name = 'name';

    public function __construct($central_controller, $pdo)
    {
        $this->model = new TagsModel($pdo);
        $specific_actions = [
            'create' => true,
            'update' => true,
            'delete' => true,
        ];
        parent::__construct($central_controller, $specific_actions);
    }

    // GETTERS

    public function getById($id)
    {
        return $this->model->getOne(column: $this->id, value: $id);
    }

    public function getByName($name)
    {
        return $this->model->getByName($this->name, $name);
    }

    // Other CRUDs 

    //REBECCA

    // public function create($tokens = null, ...$data)
    public function create($data)
    {
        $isValid = $this->validateData("create", $data);
        $name = $data["name"];
        $gameId = $data["gameId"];
        // $isValidArray = json_decode($isValid, true);

        // if ($isValidArray['isSuccessful']) {
        if ($isValid) {
            $tagExists = $this->model->getOne("name", $name);

            if ($tagExists) {
                $newData = ['gameId' => $gameId, 'tagId' => $tagExists["id"], 'name' => $name];
            } else {
                if ($this->model->create(['name' => $name])) {
                    $createdTag = $this->model->getOne("name", $name);
                    $newData = ['gameId' => $gameId, 'tagId' => $createdTag["id"], 'name' => $name];
                } else {
                    throw new Exception('Failed to create new tag');
                    // return $this->createResponse(false, 'Failed to create new tag');
                }
            }
            return $this->getGamesTagsController()->create($newData);
        }
        return false;
    }

    public function delete($data)
    {
        return parent::delete($data);
    }

    public function update($data)
    {
        return parent::update($data);
    }


    // public function delete($data)
    // {
    //     $isValid = $this->validateData("delete", $data);
    //     $name = $data["name"];
    //     $gameId = $data["gameId"];
    //     $isValidArray = json_decode($isValid, true);

    //     if ($isValidArray['isSuccessful']) {

    //         $tagExists = $this->model->getOne("name", $name);

    //         if ($tagExists) {
    //             $tagId = $tagExists["id"];
    //             $filters = [
    //                 'gameId' => $gameId,
    //                 'tagId' => $tagId,
    //             ];
    //             $gameTagsRelation = $this->getGamesTagsController()->getAllMatching($filters);

    //             if (!empty($gameTagsRelation) && $this->model->delete($tagId)) {
    //                 // echo "gameTagsRelation ". print_r($gameTagsRelation, true). "<br>";
    //                 if ($this->getGamesTagsController()->delete($gameTagsRelation[0]["id"])) {
    //                     return true;
    //                     // return $this->createResponse(true, 'tags successfully deleted in gameTags and tags table');
    //                 }
    //             }
    //             throw new Exception('tag deletion unsuccessful');
    //             // return $this->createResponse(false, 'tag deletion unsuccessful');
    //         }
    //         throw new Exception('Cannot delete tag that doesnt exist');
    //         // return $this->createResponse(false, 'Cannot delete tag that doesnt exist');
    //     }
    //     return true;
    //     // return $isValidArray['isSuccessful'];
    // }



    // public function update($id = null, $tokens = null, ...$data)
    // public function update($data)
    // {
    //     $isValid = $this->validateData("update", $data);
    //     // $isValidArray = json_decode($isValid, true);
    //     $id = $data['id'];

    //     if ($isValid) {
    //         $newName = $data["newName"];
    //         $similarTagExist = $this->model->getOne("name", $newName);
    //         if ($similarTagExist) {
    //             return $this->createResponse(false, 'There is already a tag with that same name');
    //         }
    //         $newData = ['id' => $id, 'name' => $newName];
    //         if ($this->model->update($id, $newData)) {
    //             return $this->createResponse(true, 'Tag has been updated');
    //         } else {
    //             return $this->createResponse(false, 'Failed to update tag');
    //         }
    //     } else {
    //         return $isValid;
    //     }
    // }

    public function validateData($action, $data)
    {
        $id = $data["id"] ?? null;
        $name = $data["name"] ?? null;
        $gameId = $data["gameId"] ?? null;
        $oldName = $data["oldName"] ?? null;
        $newName = $data["newName"] ?? null;

        // $gameExists = $this->getGamesController()->getOne(['id' => $gameId]);
        $gameExists = $this->getOneFrom('games', 'id', $gameId);

        $isEmpty_forCreateDelete = empty($name) || empty($gameId);
        $isEmpty_forUpdate = empty($id) || empty($oldName) || empty($newName) || empty($gameId);

        if (!$gameExists) {
            throw new Exception("Game does not exist");
            // return $this->createResponse(false, 'Game does not exist');
        }
        switch ($action) {
            case "create":
            case "delete":
                if ($isEmpty_forCreateDelete) {
                    throw new Exception("Missing info to create or delete tag");
                    // return $this->createResponse(false, 'Missing info to create or delete tag');
                }
                // return $this->createResponse(true, 'isValid');
                return true;

            case "update":
                if ($isEmpty_forUpdate || !$this->model->getOne("name", $oldName)) {
                    throw new Exception("Missing info to create or delete tag");
                    // return $this->createResponse(false, 'Missing info to update tag or tag does not exist');
                }
                // return $this->createResponse(true, 'isValid');
                return true;

            default:
                throw new Exception('error in tags_controller');
                // return $this->createResponse(false, 'error in tags_controller');
        }
    }
}
