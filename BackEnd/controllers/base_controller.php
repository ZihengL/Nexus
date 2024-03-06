<?php

class BaseController
{
    public static $controllers = [];

    protected $actions = [
        'getOne',
        'getAll',
        'getAllMatching',
        'create',
        'update',
        'delete'
    ];

    protected $central_controller;
    protected $model;
    protected $restricted_columns = [];

    protected $id = 'id';

    public function __construct($central_controller, $table_specific_actions = [])
    {
        $this->central_controller = $central_controller;

        self::$controllers[$this->model->table] = $this;
        $this->actions = array_merge($this->actions, $table_specific_actions);
    }


    /*******************************************************************/
    /****************************** GETTERS ****************************/
    /*******************************************************************/

    public function isValidAction($action)
    {
        return in_array($action, $this->actions);
    }

    public function getTableName()
    {
        return $this->model->table;
    }

    public function getTableController($table)
    {
        return self::$controllers[$table];
    }

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
    /****************** VALIDATION, ACCESS & SECURITY ******************/
    /*******************************************************************/
    public function standardizeRequestData($data)
    {
        $client_to_server = [
            'columnName' => 'column',
            'includedColumns' => 'included_columns'
        ];

        foreach ($client_to_server as $client_value => $server_value)
            if (array_key_exists($client_value, $data)) {
                $data[$server_value] = $data[$client_value];
                unset($data[$client_value]);
            }

        return $data;
    }

    protected function filterAccess($included_columns = [])
    {
        $valid_columns = array_diff($this->model->getColumns(true), $this->restricted_columns);

        // if (!is_array($included_columns) || empty($included_columns))
        // return $valid_columns;

        return empty($included_columns) ? $valid_columns : array_intersect($valid_columns, $included_columns);
    }

    protected function filterAccessOnJoins($joined_tables = [])
    {
        if (is_array($joined_tables))
            foreach ($joined_tables as $ext_tab => $included_columns)
                if ($table_controller = self::$controllers[$ext_tab])
                    $included_columns = $table_controller->filterAccess($included_columns);

        return $joined_tables;
    }

    protected function authenticate($user_id, $tokens)
    {
        if ($validated_tokens = $this->getTokensController()->validateTokens($user_id, $tokens))
            return $validated_tokens;

        throw new Exception("Invalid authentication tokens provided for User id '$user_id'.");
    }

    protected function getTokenSub($jwts)
    {
        return $this->getTokensController()->getTokenSub($jwts);
    }



    /*******************************************************************/
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    {
        $included_columns = $this->filterAccess($included_columns);
        $joined_tables = $this->filterAccessOnJoins($joined_tables);

        return $this->model->getOne($column, $value, $included_columns, $joined_tables);
    }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        $included_columns = $this->filterAccess($included_columns);
        $joined_tables = $this->filterAccessOnJoins($joined_tables);

        return $this->model->getAll($column, $value, $included_columns, $sorting, $joined_tables);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        $included_columns = $this->filterAccess($included_columns);
        $joined_tables = $this->filterAccessOnJoins($joined_tables);

        return $this->model->getAllMatching($filters, $sorting, $included_columns, $joined_tables, $joined_tables);
    }

    public function create(...$data)
    {
        return $this->model->create($data);
    }

    public function update(...$data)
    {
        $id = $data[$this->id];
        return $this->model->update($id, $data);
    }

    public function delete(...$data)
    {
        $id = $data[$this->id];
        return $this->model->delete($id, $data);
    }


    /*******************************************************************/
    /***************************** LOGGING *****************************/
    /*******************************************************************/

    public function createResponse($isSuccess, $message)
    {
        if (!$isSuccess)
            throw new Exception($message);

        $response = [
            'isSuccessful' => (bool) $isSuccess,
            'message' => $message,
        ];

        return $response;

        // return json_encode($response);
    }

    // public function createResponse($isSuccess, $message)
    // {
    //     $response = [
    //         'isSuccessful' => (bool) $isSuccess,
    //         'message' => $message,
    //     ];

    //     return json_encode($response);
    // }

    // protected function getOne($data, $mandatory_entries = [])
    // {
    //     $mandatory_entries = [...$mandatory_entries, 'column', 'value'];

    //     if ($this->validateMandatoryData($mandatory_entries, $data)) {
    //         [
    //             'column' => $column,
    //             'value' => $value,
    //             'includedColumns' => $included_columns,
    //             'joinedTables' => $joined_tables
    //         ] = $data;

    //         return $this->model->getOne($column, $value, $included_columns, $joined_tables);
    //     }

    //     return null;
    // }

    // protected function getAll($data, $mandatory_entries = [])
    // {
    //     if ($this->validateMandatoryData($data, $mandatory_entries)) {
    //         [
    //             'column' => $column,
    //             'value' => $value,
    //             'includedColumns' => $included_columns,
    //             'sorting' => $sorting,
    //             'joinedTables' => $joined_tables
    //         ] = $data;

    //         return $this->model->getAll($column, $value, $included_columns, $sorting, $joined_tables);
    //     }

    //     return null;
    // }

    // protected function getAllMatching($data, $mandatory_entries = [])
    // {
    //     if ($this->validateMandatoryData($data, $mandatory_entries)) {
    //         [
    //             'filters' => $filters,
    //             'sorting' => $sorting,
    //             'includedColumns' => $included_columns,
    //             'joinedTables' => $joined_tables
    //         ] = $data;

    //         return $this->model->getAllMatching($filters, $sorting, $included_columns, $joined_tables);
    //     }

    //     return null;
    // }

    // protected function create($data, $mandatory_entries = [])
    // {
    //     if ($this->validateMandatoryData($data, $mandatory_entries)) {
    //         return $this->model->create($data);
    //     }

    //     return null;
    // }

    // protected function update($data, $mandatory_entries = [])
    // {
    //     $mandatory_entries = [...$mandatory_entries, $this->id];

    //     if ($this->validateMandatoryData($data, $mandatory_entries)) {
    //         $id = $data[$this->id];

    //         return $this->model->update($id, $data);
    //     }
    // }

    // protected function delete($data, $mandatory_entries = [])
    // {
    //     $mandatory_entries = [...$mandatory_entries, $this->id];

    //     if ($this->validateMandatoryData($data, $mandatory_entries)) {
    //         $id = $data[$this->id];

    //         return $this->model->delete($id);
    //     }
    // }
}
