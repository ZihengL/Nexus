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

    // Managers

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

    // Controllers

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

    protected function getGameTagsController()
    {
        return $this->central_controller->gametags_controller;
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
        foreach ($this->central_controller->controllers_array as $controller)
            if ($controller->getTableName() === $table)
                return $controller;

        return null;
    }

    public function restrictAccess($included_columns = [])
    {
        if (!is_array($included_columns) || empty($included_columns))
            $included_columns = $this->model->getColumns(true);

        return array_diff($included_columns, $this->restricted_columns);
    }

    public function restrictJoinedTables($joined_tables = [])
    {
        if (is_array($joined_tables))
            foreach ($joined_tables as $ext_tab => $included_columns)
                if ($table_controller = $this->getTableController($ext_tab))
                    $included_columns = $table_controller->restrictAccess($included_columns);

        return $joined_tables;
    }

    /*******************************************************************/
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    {
        $included_columns = $this->restrictAccess($included_columns);
        $joined_tables = $this->restrictJoinedTables($joined_tables);

        return $this->model->getOne($column, $value, $included_columns, $joined_tables);
    }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        $included_columns = $this->restrictAccess($included_columns);
        $joined_tables = $this->restrictJoinedTables($joined_tables);

        return $this->model->getAll($column, $value, $included_columns, $sorting, $joined_tables);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        $included_columns = $this->restrictAccess($included_columns);
        $joined_tables = $this->restrictJoinedTables($joined_tables);

        printall($included_columns);
        printall($joined_tables);

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
