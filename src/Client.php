<?php

namespace Golden\OpenApi;

use Golden\OpenApi\Helpers\ToolsHelper;

class Client
{
    const HOST = 'https://api-bigdata.wetax.com.cn';

    protected $appKey;
    protected $secret;

    public function __construct(string $appKey, string $secret)
    {
        $this->appKey = $appKey;
        $this->secret = $secret;
    }

    public function query(int $apiId, array $params = [])
    {
        $uri = '/api/open-api/' . $apiId;

        return $this->request($uri, $params);
    }

    protected function request(string $uri, array $params)
    {
        $params += [
            'cx_app_key'   => $this->appKey,
            'cx_timestamp' => time(),
            'cx_nonce_str' => mt_rand(10000000000, 99999999999),
        ];

        $params['cx_signature'] = ToolsHelper::buildSignature($params, $this->secret);

        [$response] = ToolsHelper::guzHttpRequest(self::HOST . $uri, $params, 'POST');

        if ($response['code'] != 0) {
            throw new \Exception($response['message']);
        }

        return $response['data'] ?? [];
    }
}
