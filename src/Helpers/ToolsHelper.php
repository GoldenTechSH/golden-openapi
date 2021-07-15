<?php

namespace Golden\OpenApi\Helpers;

class ToolsHelper
{
    public static function buildSignature(array $params, string $secretKey)
    {
        // 键名升序
        ksort($params);

        $strs = [];

        foreach ($params as $key => $value) {
            $strs[] = $key . '=' . (is_array($value) ? json_encode($value) : $value);
        }

        // 拼接待签名字符串
        $paramStr = implode('&', $strs);

        // 构造签名
        $signStr = base64_encode(hash_hmac('sha256', $paramStr, $secretKey, true));

        return urlencode($signStr);
    }

    public static function guzHttpRequest(
        string $url,
        $params,
        string $method = 'POST',
        string $format = null,
        array $headers = [],
        string $respType = 'JSON',
        array $guzConfig = []
    ) {
        // @see http://guzzle-cn.readthedocs.io/zh_CN/latest
        $http = new \GuzzleHttp\Client(['verify' => false, 'headers' => $headers] + $guzConfig);

        $data = [$method == 'POST' ? 'form_params' : 'query' => $params];

        if ($format == 'JSON') {
            $data = ['json' => $params];
        }
        elseif ($format == 'RAW') {
            $data = ['body' => $params];
        }
        // 完全自定义
        elseif ($format == 'CUST') {
            $data = $params;
        }

        // 当前时间
        $nowMs = microtime(true);

        $response = $http->request($method, $url, $data);

        $respCode = $response->getStatusCode();
        $respBody = $response->getBody()->getContents();

        $result = null;

        if ($respType === 'JSON' && $respBody) {
            $result = json_decode($respBody, true);
            if (($result === false || $result === null) && json_last_error() !== JSON_ERROR_NONE) {
                throwx('解析响应 JSON 异常：' . json_last_error_msg());
            }
        }

        return [$result, $respBody, $respCode];
    }
}
