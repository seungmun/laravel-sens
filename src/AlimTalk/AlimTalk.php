<?php


namespace Seungmun\Sens\AlimTalk;


use Exception;
use Seungmun\Sens\Exceptions\SensException;
use Seungmun\Sens\Sens;

class AlimTalk extends Sens
{
    public function __construct(array $config)
    {
        $this->httpClient();

        $this->setServiceId($config['alimtalk_service_id'])
            ->setAccessKey($config['access_key'])
            ->setSecretKey($config['secret_key']);

        $this->config = $config;

    }

    /**
     * @param array $params
     * @throws SensException
     */
    public function send(array $params)
    {

        if ( ! $this->assertValidTokens()) {
            throw SensException::InvalidNCPTokens("NCP tokens are invalid.");
        }

        $uri = '{method} https://sens.apigw.ntruss.com/alimtalk/v2/services/{service}/messages';

        $endpoint = $this->resolveEndpoint($uri, [
            'method' => 'POST',
            'service' => $this->getServiceId(),
        ]);

        try {
            $json_encode = json_encode($params);
            $this->httpClient()->post($endpoint['url'], [
                'headers' => $this->prepareRequestHeaders(
                    $endpoint['method'],
                    $endpoint['path']
                ),
                'body' => $json_encode,
            ]);
        } catch ( Exception $e) {
            throw new SensException($e);
        }
    }
}
