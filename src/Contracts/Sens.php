<?php

namespace Seungmun\Sens\Contracts;

interface Sens
{
    /** @var string */
    const X_NCP_APIGW_TIMESTAMP = 'x-ncp-apigw-timestamp';

    /** @var string */
    const X_NCP_IAM_ACCESS_KEY = 'x-ncp-iam-access-key';

    /** @var string */
    const X_NCP_APIGW_SIGNATURE_V2 = 'x-ncp-apigw-signature-v2';

    /**
     * Handle the send action.
     *
     * @param  array  $params
     * @return void
     */
    public function send(array $params);
}