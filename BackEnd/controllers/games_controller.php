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

    // TODO: LINK BACK TO USER AUTH
    public function create($data, $jwts = null)
    {
        return $this->model->create($data);
    }

    public function update($id, $data, $jwts = null)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id, $jwts = null)
    {
        return $this->model->delete($id);
    }
}
