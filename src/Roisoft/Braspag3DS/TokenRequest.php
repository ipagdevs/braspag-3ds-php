<?php

namespace Roisoft\Braspag3DS;

use Roisoft\Braspag3DS\Environment;
use Roisoft\Braspag3DS\Credentials;
use Roisoft\Braspag3DS\TokenResponseData;
use Psr\Log\LoggerInterface;

/**
 * Class TokenRequest
 *
 * @package Roisoft\Braspag3DS
 */
class TokenRequest
{

    private $credentials;
    private $environment;
    private $logger;

	/**
	 * TokenRequest constructor.
	 *
	 * @param Credentials $credentials
	 * @param Environment $environment
	 * @param LoggerInterface|null $logger
	 */
    public function __construct(Credentials $credentials, Environment $environment, LoggerInterface $logger = null)
    {
        $this->credentials = $credentials;
        $this->environment = $environment;
        $this->logger = $logger;
    }

    /**
     * @param $data
     *
     * @return null
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function execute($data)
    {
        $url = $this->environment->getApiUrl() . 'v2/auth/token';

        return $this->sendRequest('POST', $url, $data);
    }
    
    /**
     * @param                        $method
     * @param                        $url
     * @param \JsonSerializable|null $content
     *
     * @return mixed
     *
     * @throws \Exception
     * @throws \RuntimeException
     */
    protected function sendRequest($method, $url, \JsonSerializable $content = null)
    {
        $headers = [
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'User-Agent: App/1.0 PHP SDK',
            'Authorization: Basic ' . $this->getBasicAuthorization()
        ];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        if ($content !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($content));

            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Length: 0';
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($this->logger !== null) {
            $this->logger->debug('Requisição', [
                    sprintf('%s %s', $method, $url),
                    $headers,
                    json_decode(preg_replace('/("cardnumber"):"([^"]{6})[^"]+([^"]{4})"/i', '$1:"$2******$3"', json_encode($content)))
                ]
            );
        }

        $response   = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($this->logger !== null) {
            $this->logger->debug('Resposta', [
                sprintf('Código de status: %s', $statusCode),
                json_decode($response)
            ]);
        }

        if (curl_errno($curl)) {
            $message = sprintf('cURL error[%s]: %s', curl_errno($curl), curl_error($curl));

            if ($this->logger !== null) {
                $this->logger->error($message);
            }

            throw new \RuntimeException($message);
        }

        curl_close($curl);

        return $this->readResponse($statusCode, $response);
    }

    /**
     * @param $statusCode
     * @param $responseBody
     *
     * @return mixed
     *
     * @throws CieloRequestException
     */
    protected function readResponse($statusCode, $responseBody)
    {
        $unserialized = null;

        switch ($statusCode) {
            case 200:
            case 201:
                $unserialized = $this->unserialize($responseBody);
                break;
            default:
                throw new \Exception('Unknown status', $statusCode);
        }

        return $unserialized;
    }

    /**
     * @param $json
     *
     * @return Sale
     */
    protected function unserialize($json)
    {
        return TokenResponseData::fromJson($json);
    }
    
    protected function getBasicAuthorization() {
        return base64_encode($this->credentials->getId() . ':' . $this->credentials->getKey());
    }
}
