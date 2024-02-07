<?php

class Routines
{
    private static $instance = null;

    private const REFRESH_RATE = 3600;

    private $run = true;

    private function __construct()
    {
    }

    public static function instanciateRoutines($state)
    {
        if (self::$instance === null) {
            self::$instance = new Routines();
        }

        self::$instance->run = $state;
        if (self::$instance->run) {
        }
    }

    private function runRoutines()
    {
        if ($this->run) {
            // TODO: cleanup script
        }

        sleep(self::REFRESH_RATE);
        $this->runRoutines();
    }
}
