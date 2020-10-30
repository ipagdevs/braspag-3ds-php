<?php

namespace Roisoft\Braspag3DS;

/**
 * Class Environment
 *
 * @package Roisoft\Braspag3DS
 */
class Environment 
{
    private $api;

    /**
     * Environment constructor.
     *
     * @param $api
     * @param $apiQuery
     */
    private function __construct($api)
    {
        $this->api      = $api;
    }

    /**
     * @return Environment
     */
    public static function sandbox()
    {
        $api      = 'https://mpisandbox.braspag.com.br/';

        return new Environment($api);
    }

    /**
     * @return Environment
     */
    public static function production()
    {
        $api      = 'https://mpi.braspag.com.br/';

        return new Environment($api);
    }

    /**
     * Gets the environment's Api URL
     *
     * @return string the Api URL
     */
    public function getApiUrl()
    {
        return $this->api;
    }
    
}
