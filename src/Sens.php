<?php

namespace Seungmun\Sens;

use GuzzleHttp\Client;
use Seungmun\Sens\Contracts\Sens as SensContract;

abstract class Sens implements SensContract
{
    /** @var \GuzzleHttp\Client */
    protected $http;

    /** @var string */
    private $serviceId;

    /** @var string */
    private $accessKey;

    /** @var string */
    private $secretKey;

    /** @var array */
    private $config = [];

    /** @var array */
    private $headers = [];

    /**
     * Create a new SENS instance.
     *
     * @param  array  $config
     */
    public function __construct(array $config)
    {
        $this->httpClient();

        $this->setServiceId($config['service_id'])
            ->setAccessKey($config['access_key'])
            ->setSecretKey($config['secret_key']);

        $this->config = $config;
    }

    /**
     * Create a new HTTP Request Client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function httpClient()
    {
        return $this->http ?: $this->http = new Client();
    }

    /**
     * Determine if tokens are exists normally.
     *
     * @return bool
     */
    public function assertValidTokens()
    {
        return ! empty($this->getServiceId()) &&
            ! empty($this->getAccessKey()) &&
            ! empty($this->getSecretKey());
    }

    /**
     * Get SENS service identifier.
     *
     * @return string
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Set SENS service identifier.
     *
     * @param  string  $serviceId
     * @return \Seungmun\Sens\Sens
     */
    public function setServiceId(string $serviceId)
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    /**
     * Get SENS access key.
     *
     * @return string
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * Set SENS access key.
     *
     * @param  string  $accessKey
     * @return \Seungmun\Sens\Sens
     */
    public function setAccessKey(string $accessKey)
    {
        $this->accessKey = $accessKey;

        return $this;
    }

    /**
     * Get SENS secret key.
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set SENS secret key.
     *
     * @param  string  $secretKey
     * @return \Seungmun\Sens\Sens
     */
    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Resolve the given uri to http request url.
     *
     * @param  string  $uri
     * @param  array  $params
     * @return array
     */
    public function resolveEndpoint($uri, $params)
    {
        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }

        $tokens = explode(' ', $uri);

        return [
            'method' => $tokens[0],
            'url' => $tokens[1],
            'path' => parse_url($tokens[1], PHP_URL_PATH),
            'host' => parse_url($tokens[1], PHP_URL_HOST),
        ];
    }

    /**
     * Prepare HTTP headers for request NCLOUD API v2 authentication.
     *
     * @param  string  $method
     * @param  string  $uri
     * @return array
     */
    public function prepareRequestHeaders($method, $uri)
    {
        $timestamp = $this->timestamp();

        $this->addHeader('Content-Type', 'application/json; charset=utf-8');
        $this->addHeader(self::X_NCP_APIGW_TIMESTAMP, $timestamp);
        $this->addHeader(self::X_NCP_IAM_ACCESS_KEY, $this->getAccessKey());
        $this->addHeader(self::X_NCP_APIGW_SIGNATURE_V2, $this->makeSignature($method, $uri, $timestamp));

        return $this->headers();
    }

    /**
     * Get current timestamp to compare api server.
     *
     * @return string
     */
    protected function timestamp()
    {
        return strval((int)round(microtime(true) * 1000));
    }

    /**
     * Add a new HTTP header attribute.
     *
     * @param  string  $key
     * @param  string  $value
     * @return $this
     */
    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * generate x-ncp-apigw-signature-v2 token for authentication.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  string  $timestamp
     * @return string
     */
    public function makeSignature($method, $uri, $timestamp)
    {
        $buffer = [];

        // Important - do not change these all lines down here ever!
        array_push($buffer, strtoupper($method) . " " . $uri);
        array_push($buffer, $timestamp);
        array_push($buffer, $this->getAccessKey());

        $secretKey = utf8_encode($this->getSecretKey());
        $message = utf8_encode(implode("\n", $buffer));
        $hash = hex2bin(hash_hmac('sha256', $message, $secretKey));

        return base64_encode($hash);
    }

    /**
     * HTTP Header Attributes
     *
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Remove the given HTTP header.
     *
     * @param  string  $key
     * @return \Seungmun\Sens\Sens
     */
    public function removeHeader(string $key)
    {
        unset($this->headers[$key]);

        return $this;
    }
}