<?php

class BaseController
{
    protected static $controllers = [];

    protected $actions = [
        'getOne' => false,
        'getAll' => false,
        'getAllMatching' => false,
        'create' => false,
        'update' => false,
        'delete' => false
    ];

    protected $central_controller;
    protected $model;
    protected $restricted_columns = [];

    protected $id = 'id';

    public function __construct($central_controller, $table_specific_actions = [])
    {
        $this->central_controller = $central_controller;
        $this->actions = array_merge($this->actions, $table_specific_actions);

        // echo '<hr>' . $this->model->table;
        // foreach ($this->actions as $action => $value) {
        //     // $this->actions[$action] ??= false;
        //     echo '<br>' . $action . ' ' . ($value === false ? 'public' : 'private');
        // }

        self::$controllers[$this->model->table] = $this;
    }


    /*******************************************************************/
    /****************************** GETTERS ****************************/
    /*******************************************************************/

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

    protected function getGamesTagsController()
    {
        return $this->central_controller->gamestags_controller;
    }


    /*******************************************************************/
    /****************** VALIDATION, ACCESS & SECURITY ******************/
    /*******************************************************************/

    public static function getController($table)
    {
        if (isset(self::$controllers[$table]))
            return self::$controllers[$table];

        throw new Exception("Unable to find request table '$table'.");
    }

    public function isValidAction($action)
    {
        if (in_array($action, array_keys($this->actions)))
            return true;

        $imploded = implode(', ', array_keys($this->actions));
        throw new Exception("Innaplicable request action '$action' from '$imploded'.");
    }

    public function isPrivilegedAction($action)
    {
        return $this->actions[$action];
    }

    // TODO: CHANGING FROM AUTHENTICATION TO JUST VALIDATION?
    public function verifyCredentials($data)
    {
        [$id, $tokens, $data] = getFromData(['id', 'tokens'], $data, true);

        // if ($authenticated_tokens = $this->authenticateUser(...$credentials))
        if ($this->validateUser($id, $tokens))
            return $data;

        return false;
    }

    protected function validateUser($id, $tokens)
    {
        return $this->getTokensController()->validateTokens($id, $tokens);
    }

    protected function authenticateUser($id, $tokens)
    {
        return $this->getTokensController()->authenticateTokens($id, $tokens);
    }


    /*******************************************************************/
    /************************* PROCESSING DATA *************************/
    /*******************************************************************/

    protected function setGetterDefaults($data)
    {
        $data['included_columns'] ??= [];
        $data['included_columns'] = $this->filterAccess($data['included_columns']);

        $data['joined_tables'] ??= [];
        $data['joined_tables'] = $this->filterAccessOnJoins($data['joined_tables']);

        $data['paging'] ??= ['limit' => -1, 'offset' => 0];

        return $data;
    }

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

    // Need this to filter out indirect access to restricted columns
    protected function filterAccess($included_columns = [])
    {
        $valid_columns = array_diff($this->model->getColumns(true), $this->restricted_columns);

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


    /*******************************************************************/
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    public function getOne($data)
    {
        return $this->model->getOne(...$this->setGetterDefaults($data));
    }

    public function getAll($data)
    {
        return $this->model->getAll(...$this->setGetterDefaults($data));
    }

    public function getAllMatching($data)
    {
        return $this->model->getAllMatching(...$this->setGetterDefaults($data));
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($data)
    {
        $id = getOneFromData($this->id, $data);

        if ($this->model->update($id, $data)) {
            return $this->model->getOne($this->id, $id);
        }

        return false;
    }

    public function delete($data)
    {
        $id = getOneFromData($this->id, $data);

        return $this->model->delete($id);
    }


    /*******************************************************************/
    /****************************** TOOLS ******************************/
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

    protected function getUserIdFromTokens($tokens)
    {
        return $this->getTokensController()->getTokenSub($tokens);
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
