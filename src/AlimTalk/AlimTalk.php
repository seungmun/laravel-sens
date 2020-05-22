<?php

namespace Seungmun\Sens\AlimTalk;

use Exception;
use Seungmun\Sens\Sens;
use Seungmun\Sens\Exceptions\SensException;

class AlimTalk extends Sens
{
    /**
     * @param  array  $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->setServiceId($config['alimtalk_service_id']);
    }

    /**
     * @param  array  $params
     * @return void
     *
     * @throws \Seungmun\Sens\Exceptions\SensException
     */
    public function send(array $params)
    {
        if (! $this->assertValidTokens()) {
            throw SensException::InvalidNCPTokens('NCP tokens are invalid.');
        }

        $uri = '{method} https://sens.apigw.ntruss.com/alimtalk/v2/services/{service}/messages';

        $endpoint = $this->resolveEndpoint($uri, [
            'method' => 'POST',
            'service' => $this->getServiceId(),
        ]);

        try {
            $this->httpClient()->post($endpoint['url'], [
                'headers' => $this->prepareRequestHeaders(
                    $endpoint['method'],
                    $endpoint['path']
                ),
                'body' => json_encode($params),
            ]);
        } catch (Exception $e) {
            throw new SensException($e);
        }
    }
}
