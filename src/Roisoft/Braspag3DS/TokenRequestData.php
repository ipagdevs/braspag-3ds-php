<?php

namespace Roisoft\Braspag3DS;

/**
 * Class TokenRequestData
 *
 * @package Roisoft\Braspag3DS
 */
class TokenRequestData implements \JsonSerializable
{

    private $EstablishmentCode;
    private $MerchantName;
    private $MCC;

    /**
     * Payment constructor.
     *
     * @param int $amount
     * @param int $installments
     */
    public function __construct($EstablishmentCode = null, $MerchantName = null, $MCC = null)
    {
        $this->EstablishmentCode = $EstablishmentCode;
        $this->MerchantName = $MerchantName;
        $this->MCC = $MCC;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
