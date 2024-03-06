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


    /*******************************************************************/
    /***************************** GETTERS *****************************/
    /*******************************************************************/

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        $included_columns = empty($included_columns) ? [] : $included_columns;
        $sorting = empty($sorting) ?  [$this->ratingAverage => true] : $sorting;

        return $this->model->getAll($column, $value, $included_columns, $sorting, $joined_tables);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        $sorting = empty($sorting) ?  [$this->ratingAverage => true] : $sorting;

        return parent::getAllMatching($filters, $sorting, $included_columns, $joined_tables);
    }


    /*******************************************************************/
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    public function create($tokens = null, ...$data)
    {
        if ($user_id = $this->getTokenSub($tokens)) {
            $data['developerID'] = $user_id;

            return $this->model->create($data);
        }

        return false;
    }

    public function update($id = null, $tokens = null, ...$data)
    {
        if ($user = $this->getGameDeveloper($id))
            if ($validated_tokens = $this->authenticate($user['id'], $tokens)) {
                $this->model->update($id, $data);

                return $validated_tokens;
            }

        return false;
    }

    public function delete($id, $jwts = null, ...$data)
    {
        return $this->model->delete($id);
    }

    // Tools

    public function getGameDeveloper($id)
    {
        if ($game = $this->model->getOne('id', $id))
            return $this->getUsersController()->getOne('id', $game['developerID']);

        return null;
    }
}
