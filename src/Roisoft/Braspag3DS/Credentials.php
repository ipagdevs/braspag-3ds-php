<?php

namespace Roisoft\Braspag3DS;

/**
 * Class Credentials
 */
class Credentials
{
    private $id;
    private $key;

    /**
     * Credentials constructor.
     *
     * @param $id
     * @param $key
     */
    public function __construct($id, $key)
    {
        $this->id  = $id;
        $this->key = $key;
    }

    /**
     * Gets the merchant identification number
     *
     * @return string the merchant identification number on Cielo
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the merchant identification key
     *
     * @return string the merchant identification key on Cielo
     */
    public function getKey()
    {
        return $this->key;
    }
}
