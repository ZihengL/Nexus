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
        $specific_actions = ['getOneAsJoined', 'getAllAsJoined'];
        $restricted_actions = ['create', 'update', 'delete'];
        
        parent::__construct($central_controller, $specific_actions, $restricted_actions);
    }

    protected function setGetterDefaults($data)
    {
        $data['sorting'] ??= [$this->ratingAverage => true];

        return parent::setGetterDefaults($data);
    }


    /*******************************************************************/
    /***************************** GETTERS *****************************/
    /*******************************************************************/

    public function getOneAsJoined($data)
    {
        $data = $this->setGetterDefaults($data);
        return $this->model->getOneAsJoined(...$data);
    }

    public function getAllAsJoined($data)
    {
        $data = $this->setGetterDefaults($data);
        return $this->model->getOneAsJoined(...$data);
    }


    /*******************************************************************/
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    // public function create($tokens = null, ...$data)
    public function create($data)
    {
        [$credentials, $create_data] = getFromData(['credentials'], $data, true);

        if ($authenticated_tokens = $this->authenticateUser(...$credentials)) {
            parent::create($create_data);

            return $authenticated_tokens;
        }

        return false;
    }

    // public function update($id, $tokens = null, ...$data)
    public function update($data)
    {
        [$credentials, $update_data] = getFromData(['credentials'], $data, true);

        if ($authenticated_tokens = $this->authenticateUser(...$credentials)) {
            parent::update($update_data);

            return $authenticated_tokens;
        }

        return false;
    }

    // public function delete($id, $tokens = null)
    public function delete($data)
    {
        [$credentials, $update_data] = getFromData(['credentials'], $data, true);

        return $this->model->delete($id);
    }

    // Tools

    public function getDeveloper($game_id)
    {
        if ($game = $this->model->getOne(column: 'id', value: $game_id, included_columns: ['developerID'])) {
            return $this->getUsersController()->getOne('id', $game['developerID']);
        }

        return null;
    }
}
