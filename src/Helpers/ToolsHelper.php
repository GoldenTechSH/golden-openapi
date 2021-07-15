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
}
