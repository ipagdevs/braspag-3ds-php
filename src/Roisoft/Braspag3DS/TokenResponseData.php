<?php

namespace Roisoft\Braspag3DS;

/**
 * Class TokenResponseData
 *
 * @package Roisoft\Braspag3DS
 */
class TokenResponseData implements \JsonSerializable
{

    private $access_token;
    private $token_type;
    private $expires_in;

    /**
     * Payment constructor.
     *
     * @param int $amount
     * @param int $installments
     */
    public function __construct($access_token = null, $token_type = null, $expires_in = null)
    {
        $this->access_token = $access_token;
        $this->token_type = $token_type;
        $this->expires_in = $expires_in;
    }
    
    public function getAccessToken() {
        return $this->access_token;
    }
    
    public function getExpiresIn() {
        return $this->expires_in;
    }
    
    /**
     * @param $json
     *
     * @return Sale
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $obj = new TokenResponseData();
        $obj->populate($object);

        return $obj;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $dataProps = get_object_vars($data);
        
        if (isset($dataProps['access_token'])) {
            $this->access_token = $data->access_token;
        }
        if (isset($dataProps['token_type'])) {
            $this->token_type = $data->token_type;
        }
        if (isset($dataProps['expires_in'])) {
            $this->expires_in = $data->expires_in;
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
