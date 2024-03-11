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
        throw new Exception("Innaplicable request action '$action' from available actions: '$imploded'.");
    }

    public function isPrivilegedAction($action)
    {
        return $this->actions[$action];
    }

    // TODO: CHANGING FROM AUTHENTICATION TO JUST VALIDATION?
    public function verifyCredentials($data)
    {
        ['credentials' => $credentials, 'request_data' => $request_data] = $data + [null, null];

        // if ($authenticated_tokens = $this->authenticateUser(...$credentials))
        if ($credentials) {
            ['id' => $id, 'tokens' => $tokens] = $credentials + [null, null];
            if ($this->validateUser($id, $tokens))
                return $request_data;
        }

        throw new Exception("Missing credentials in privileged operation.");
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

    protected function setGetterDefaults($data)
    {
        $data['included_columns'] ??= [];
        $data['included_columns'] = $this->filterAccess($data['included_columns']);

        $data['joined_tables'] ??= [];
        $data['joined_tables'] = $this->filterAccessOnJoins($data['joined_tables']);

        $data['paging'] ??= ['limit' => -1, 'offset' => 0];

        return $data;
    }

    protected function getDefault($key, $values = [])
    {
        switch ($key) {
            case 'included_columns':
                return $this->filterAccess($values);
            case 'joined_tables':
                return $this->filterAccessOnJoins($values);
            case 'paging':
                return ['limit' => -1, 'offset' => 0];
            default:
                return null;
        }
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

    protected function getOneFrom($table, $column, $value, $included_columns = [], $joined_tables = [])
    {
        if (isset(self::$controllers[$table])) {
            $controller = self::$controllers[$table];

            return $controller->getOne([$column, $value, $included_columns, $joined_tables]);
        }

        return null;
    }

    protected function getAllFrom($table, $column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [], $paging = [])
    {
        if (isset(self::$controllers[$table])) {
            $controller = self::$controllers[$table];

            return $controller->getAll([$column, $value, $included_columns, $sorting, $joined_tables, $paging]);
        }

        return null;
    }

    protected function getAllMatchingFrom($table, $filters = [], $sorting = [], $included_columns = [], $joined_tables = [], $paging = [])
    {
        if (isset(self::$controllers[$table])) {
            $controller = self::$controllers[$table];

            return $controller->getAllMatching([$filters, $sorting, $included_columns, $joined_tables]);
        }

        return null;
    }

    public function getOne($data)
    {
        [
            'column' => $column,
            'value' => $value,
            'included_columns' => $included_columns,
            'joined_tables' => $joined_tables
        ] = $data + [
            null,
            null,
            $this->getDefault('included_columns'),
            $this->getDefault('joined_tables')
        ];

        return $this->model->getOne($column, $value, $included_columns, $joined_tables);
    }

    public function getAll($data)
    {
        [
            'column' => $column,
            'value' => $value,
            'included_columns' => $included_columns,
            'sorting' => $sorting,
            'joined_tables' => $joined_tables,
            'paging' => $paging
        ] = $data + [
            null,
            null,
            $this->getDefault('included_columns'),
            [],
            $this->getDefault('joined_tables'),
            $this->getDefault('paging')
        ];

        return $this->model->getAll($column, $value, $included_columns, $sorting, $joined_tables, $paging);
    }

    public function getAllMatching($data)
    {
        [
            'filters' => $filters,
            'sorting' => $sorting,
            'included_columns' => $included_columns,
            'joined_tables' => $joined_tables,
            'paging' => $paging
        ] = $data + [
            [],
            [],
            $this->getDefault('included_columns'),
            $this->getDefault('joined_tables'),
            $this->getDefault('paging')
        ];

        return $this->model->getAllMatching($filters, $sorting, $included_columns, $joined_tables, $paging);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($data)
    {
        $id = $data['id'];

        if ($this->model->update($id, $data)) {
            return $this->model->getOne($this->id, $id);
        }

        return false;
    }

    public function delete($data)
    {
        $id = $data['id'];

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
