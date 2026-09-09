# ElectronicContractFdd

PHP SDK for [FaDaDa](https://www.fadada.com/) electronic contract APIs. It covers request signing, 3DES encryption, and form/file uploads. HTTP is implemented with `curl` — no third-party HTTP client.

[中文](README.md)

## Requirements

- PHP `>= 7.4`
- Extensions: `curl`, `json`, `openssl`

## Installation

```bash
composer require george-t/electronic-contract-fdd
```

From source:

```bash
git clone https://github.com/george-t/ElectronicContractFdd.git
cd ElectronicContractFdd
composer install
```

## Quick start

```php
use georgeT\ElectronicContractFdd\Config\Config;
use georgeT\ElectronicContractFdd\FddServer;

$fdd = new FddServer($appId, $appSecret);

$param = [
    'customer_id'  => $customerId,
    'verified_way' => '0',
    'page_modify'  => '1',
    'return_url'   => 'https://example.com/return',
];

$result = $fdd->request
    ->scheme('https')
    ->host('testapi.fadada.com:8443') // use the production host in live
    ->path('/get_person_verify_url.api')
    ->method('POST')
    ->inputFormat('array')
    ->outputFormat('json')
    ->options([
        'query' => array_merge([
            'app_id'     => $fdd->appId,
            'timestamp'  => $fdd->timestamp,
            'v'          => Config::VERSION,
            'msg_digest' => $fdd->buildMsgDigest($param),
        ], $param),
    ])
    ->request();

// $result is the decoded API payload (array)
```

Pass **business fields only** to `buildMsgDigest()` (omit `app_id`, `timestamp`, `v`, `msg_digest`). The SDK sorts, concatenates, and hashes them using FaDaDa’s digest rules.

## Upload a contract file

Use `multipart`:

```php
$contractId = date('YmdHis');
$msgDigest  = $fdd->buildMsgDigest([$contractId]);

$result = $fdd->request
    ->scheme('https')
    ->host('testapi.fadada.com:8443')
    ->path('/api/uploaddocs.api')
    ->method('POST')
    ->inputFormat('array')
    ->outputFormat('json')
    ->options([
        'multipart' => [
            ['name' => 'app_id', 'contents' => $fdd->appId],
            ['name' => 'timestamp', 'contents' => $fdd->timestamp],
            ['name' => 'v', 'contents' => Config::VERSION],
            ['name' => 'msg_digest', 'contents' => $msgDigest],
            ['name' => 'contract_id', 'contents' => $contractId],
            ['name' => 'doc_title', 'contents' => 'Employment Contract'],
            ['name' => 'doc_type', 'contents' => '.pdf'],
            ['name' => 'file', 'contents' => fopen('/path/to/contract.pdf', 'r')],
        ],
    ])
    ->request();
```

## Request options

```php
$fdd->request
    ->timeout(30)
    ->connectTimeout(10)
    ->debug(false)
    ->proxy('http://127.0.0.1:8888');
```

| Method / option | Purpose |
| --- | --- |
| `scheme()` / `host()` / `path()` / `method()` | URL and HTTP method |
| `inputFormat()` / `outputFormat()` | `array`, `json`, or `xml` |
| `options(['query' => []])` | Sent as `application/x-www-form-urlencoded` on POST |
| `options(['multipart' => []])` | File upload |
| `timeout()` / `connectTimeout()` | Timeouts in seconds |
| `proxy()` / `cert()` / `debug()` | Proxy, client cert, cURL verbose mode |

Default options can be passed to the constructor:

```php
$fdd = new FddServer($appId, $appSecret, [
    'timeout' => 30,
]);
```

## Helpers

```php
$fdd->encrypt($plain, $key);          // 3DES; returns [bool $ok, string $result]
$fdd->buildCode32();                  // 32-character serial
$fdd->bankInfo($name, $id, $branch);
$fdd->companyInfo($name, $creditNo, $imagePath);
$fdd->agentInfo($name, $id, $mobile, $idFrontPath);
```

## Tests

```bash
cp tests/config.php.example tests/config.php
# fill in ApiAddr, AppId, AppSecret
php7.4 vendor/bin/phpunit tests
```

`tests/` includes examples for person verification URLs, document upload, signing links, and contract viewing.

## License

MIT
