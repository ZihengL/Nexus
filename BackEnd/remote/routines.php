<?php

const BACKUPS = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/remote/backups/';

class Routines
{
    private static $instance = null;

    private const DAILY = 86400;
    private const HOURLY = 3600;

    private $run;
    private $database_manager;
    private $tokens_manager;

    // CONSTRUCTOR

    private function __construct($env)
    {
        $this->run = $env->run;
        $this->database_manager = $env->database_manager;

        if (self::$instance->run) $this->runRoutines();
    }

    public static function getInstance($env)
    {
        if (self::$instance === null) {
            self::$instance = new Routines($env);
        }

        return self::$instance;
    }

    public static function setRunningState($running)
    {
        self::$instance->run = $running;
    }

    // METHODS

    private function runRoutines()
    {
        if (!$this->run) return;

        $this->runRoutines();
    }

    private function execDailyRoutine()
    {
        if (!$this->run) return;

        // Database backup
        $this->database_manager->backup(time());
    }

    private function execHourlyRoutine()
    {
        if (!$this->run) return;
    }
}
