<?php
require_once "$path/controllers/base_controller.php";

class BaseController
{
    protected $central_controller;
    protected $model;
    protected $restricted_columns = [];

    protected $id = 'id';

    public function __construct($central_controller)
    {
        $this->central_controller = $central_controller;
    }


    /*******************************************************************/
    /****************************** GETTERS ****************************/
    /*******************************************************************/

    // MANAGERS

    protected function getDatabaseManager()
    {
        return $this->central_controller->database_manager;
    }

    protected function getGoogleClientManager()
    {
        return $this->central_controller->client_manager;
    }

    protected function getDriveController()
    {
        return $this->getGoogleClientManager()->drive_controller;
    }


    // TABLES

    protected function getUsersController()
    {
        return $this->central_controller->users_controller;
    }

    protected function getGamesController()
    {
        return $this->central_controller->games_controller;
    }

    protected function getTokensController()
    {
        return $this->central_controller->tokens_controller;
    }

    protected function getTagsController()
    {
        return $this->central_controller->tags_controller;
    }

    protected function getReviewsController()
    {
        return $this->central_controller->reviews_controller;
    }


    // MULTIPLICITY TABLES

    protected function getGamesTagsController()
    {
        return $this->central_controller->gamestags_controller;
    }


    /*******************************************************************/
    /************************ ACCESS & SECURITY ************************/
    /*******************************************************************/

    public function getTableName()
    {
        return $this->model->table;
    }

    public function getTableController($table)
    {
        return $this->central_controller->getTableController($table);
    }

    public function validateAccess($included_columns = [])
    {
        $valid_columns = array_diff($this->model->getColumns(true), $this->restricted_columns);

        if (!is_array($included_columns) || empty($included_columns))
            return $valid_columns;

        return array_intersect($valid_columns, $included_columns);
    }

    public function validateJoinedTables($joined_tables = [])
    {
        if (is_array($joined_tables))
            foreach ($joined_tables as $ext_tab => $included_columns)
                if ($table_controller = $this->getTableController($ext_tab))
                    $included_columns = $table_controller->validateAccess($included_columns);

        return $joined_tables;
    }


    /*******************************************************************/
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    // MAYBE JUST ONE PARAM FOR ALL CRUD ACTIONS IN BASE CONTROLLER OR CHECK (...PARAMS)
    public function parseRequest($action, $options)
    {
        try {
            [
                'crud_action' => $method,
                'included_columns' => $included_columns,
                'joined_tables' => $joined_tables
            ] = $options;

            switch ($method) {
                case 'getOne':
                    ['column' => $column, 'value' => $value] = $options;
                    return $this->getOne($column, $value, $included_columns, $joined_tables);
                case 'getAll':
                    ['column' => $column, 'value' => $value, 'sorting' => $sorting] = $options;
                    return $this->getAll($column, $value, $included_columns, $sorting, $joined_tables);
                case 'getAllMatching':
                    ['filters' => $filters, 'sorting' => $sorting] = $options;
                    return $this->getAllMatching($filters, $sorting, $included_columns, $joined_tables);
                default:
                    return "No method matching '$method' found.";
            }
        } catch (Exception $e) {
            throw new Exception("Error while parsing request: " . $e->getMessage());
        }
    }

    public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    {
        $included_columns = $this->validateAccess($included_columns);
        $joined_tables = $this->validateJoinedTables($joined_tables);

        return $this->model->getOne($column, $value, $included_columns, $joined_tables);
    }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        $included_columns = $this->validateAccess($included_columns);
        $joined_tables = $this->validateJoinedTables($joined_tables);

        return $this->model->getAll($column, $value, $included_columns, $sorting, $joined_tables);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        $included_columns = $this->validateAccess($included_columns);
        $joined_tables = $this->validateJoinedTables($joined_tables);

        return $this->model->getAllMatching($filters, $sorting, $included_columns, $joined_tables, $joined_tables);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }


    /*******************************************************************/
    /***************************** LOGGING *****************************/
    /*******************************************************************/

    public function createResponse($isSuccess, $message)
    {
        $response = [
            'isSuccessful' => (bool) $isSuccess,
            'message' => $message,
        ];

        return json_encode($response);
    }
}
