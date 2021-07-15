### Installation

```
composer require goldentech-sh/golden-openapi
```

### Usage

````
    $clientKey    = 'clientKey';
    $clientSecret = 'clientSecret';

    $apiId = 10081;
    $params = [
        'username'  => ['阿康'],
        'page'      => 1,
        'page_size' => 5,
    ];

    $openApi = new \Golden\OpenApi\Client($clientKey, $clientSecret);

    $openApi->query($apiId, $params);
```
