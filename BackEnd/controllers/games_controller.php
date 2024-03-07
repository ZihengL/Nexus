<?php
require_once "$path/controllers/base_controller.php";
require_once "$path/models/games_model.php";

class GamesController extends BaseController
{
    protected $developerID = 'developerID';
    protected $stripeID = 'stripeID';
    protected $title = 'title';
    protected $description = 'description';
    protected $ratingAverage = 'ratingAverage';
    protected $files = 'files';
    protected $media = 'media';
    protected $releaseDate = 'releaseDate';

    public function __construct($central_controller, $pdo)
    {
        $this->model = new GamesModel($pdo);
        parent::__construct($central_controller);
    }

    protected function setGetterDefaults($data)
    {
        $data['sorting'] ??= [$this->ratingAverage => true];

        return parent::setGetterDefaults($data);
    }


    /*******************************************************************/
    /***************************** GETTERS *****************************/
    /*******************************************************************/

    public function getOneAsJoined(...$data)
    {
        $data = $this->setGetterDefaults($data);
        return $this->model->getOneAsJoined(...$data);
    }

    public function getAllAsJoined(...$data)
    {
        $data = $this->setGetterDefaults($data);
        return $this->model->getOneAsJoined(...$data);
    }


    /*******************************************************************/
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    public function create($tokens = null, ...$data)
    {
        // if ($user_id = $this->getUserIdFromToken($tokens)) {
        //     $data['developerID'] = $user_id;
        if ($authenticated_tokens = $this->authenticate($data['developerID'], $tokens)) {
            $this->model->create($data);

            return $authenticated_tokens;
        }

        return false;
    }

    public function update($id, $tokens = null, ...$data)
    {
        if ($user = $this->getDeveloper($id))
            if ($authenticated_tokens = $this->authenticate($user['id'], $tokens)) {
                $this->model->update($id, $data);

                return $authenticated_tokens;
            }

        return false;
    }

    public function delete($id, $tokens = null, ...$data)
    {
        return $this->model->delete($id);
    }

    // Tools

    public function getDeveloper($game_id)
    {
        if ($game = $this->model->getOne('id', $game_id, included_columns: ['developerID'])) {
            return $this->getUsersController()->getOne('id', $game['developerID']);
        }

        return null;
    }
}
