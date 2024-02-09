<?php

class Routines
{
    private static $instance = null;

    private const DAILY = 86400;
    private const HOURLY = 3600;

    // TIMEOUTS
    private $daily_timeout;
    private $hourly_timeout;

    public $run;
    private $central_controller;

    // CONSTRUCTOR
    // TODO: Check procedure for system batch job based routines

    private function __construct($config)
    {
        $this->run = $config->run;
        $this->central_controller = $config->central_controller;

        $this->daily_timeout = false;
        $this->hourly_timeout = false;

        $this->runRoutines();
    }

    public static function getInstance($config)
    {
        if (self::$instance === null) {
            self::$instance = new Routines($config);
        }

        return self::$instance;
    }

    // GETTERS, SETTERS & VALIDATION

    public function setRunningState($running)
    {
        $this->run = $running;

        $this->runRoutines();
    }

    private function setTimeout($isDailyRoutine = false)
    {
        if ($isDailyRoutine) {
            $this->daily_timeout = true;
            return self::DAILY;
        } else {
            $this->hourly_timeout = true;
            return self::HOURLY;
        }
    }

    public function isValidForExecution($isDailyRoutine = false)
    {
        $timeout = $isDailyRoutine ? $this->daily_timeout : $this->hourly_timeout;

        return $this->run && $timeout === false;
    }

    // METHODS

    public function runRoutines()
    {
        $this->runDailyRoutine();
        $this->runHourlyRoutine();
    }

    public function runDailyRoutine()
    {
        if (!$this->isValidForExecution(false)) return;

        $this->execDailyRoutine();
        sleep($this->setTimeout(true));
        $this->runDailyRoutine();
    }

    private function execDailyRoutine()
    {
        // Database backup
        $this->central_controller->database_manager->backup(time());
    }

    public function runHourlyRoutine()
    {
        if (!$this->isValidForExecution(true)) return;

        $this->execHourlyRoutine();
        sleep($this->setTimeout(false));
        $this->runHourlyRoutine();
    }

    private function execHourlyRoutine()
    {
        // Deleting expired tokens
        $this->central_controller->token_manager->deleteExpiredTokens();
    }
}
