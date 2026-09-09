# ElectronicContractFdd

对接 [法大大](https://www.fadada.com/) 电子合同接口的 PHP SDK。封装了请求签名、3DES 加密、表单/文件上传，HTTP 层使用 `curl`，无第三方 HTTP 依赖。

[English](README.en.md)

## 环境要求

- PHP `>= 7.4`
- 扩展：`curl`、`json`、`openssl`

## 安装

```bash
composer require george-t/electronic-contract-fdd
```

源码安装：

```bash
git clone https://github.com/george-t/ElectronicContractFdd.git
cd ElectronicContractFdd
composer install
```

## 快速开始

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
    ->host('testapi.fadada.com:8443') // 生产环境替换为正式域名
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

// $result 为接口返回的数组
```

`buildMsgDigest()` 只传入**业务参数**（不含 `app_id` / `timestamp` / `v` / `msg_digest`）。SDK 会按法大大规则排序、拼接并计算摘要。

## 上传合同文件

文件字段使用 `multipart`：

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
            ['name' => 'doc_title', 'contents' => '劳动合同'],
            ['name' => 'doc_type', 'contents' => '.pdf'],
            ['name' => 'file', 'contents' => fopen('/path/to/contract.pdf', 'r')],
        ],
    ])
    ->request();
```

## 请求配置

链式调用可叠加超时、代理、证书等选项：

```php
$fdd->request
    ->timeout(30)
    ->connectTimeout(10)
    ->debug(false)
    ->proxy('http://127.0.0.1:8888');
```

| 方法 / 选项 | 说明 |
| --- | --- |
| `scheme()` / `host()` / `path()` / `method()` | 请求地址与方法 |
| `inputFormat()` / `outputFormat()` | 入参/出参格式：`array`、`json`、`xml` |
| `options(['query' => []])` | POST 时转为 `application/x-www-form-urlencoded` |
| `options(['multipart' => []])` | 文件上传 |
| `timeout()` / `connectTimeout()` | 超时（秒） |
| `proxy()` / `cert()` / `debug()` | 代理、客户端证书、cURL 调试 |

构造 `FddServer` 时也可传入默认选项：

```php
$fdd = new FddServer($appId, $appSecret, [
    'timeout' => 30,
]);
```

## 辅助方法

```php
$fdd->encrypt($plain, $key);          // 3DES，返回 [bool $ok, string $result]
$fdd->buildCode32();                  // 32 位流水号
$fdd->bankInfo($name, $id, $branch);
$fdd->companyInfo($name, $creditNo, $imagePath);
$fdd->agentInfo($name, $id, $mobile, $idFrontPath);
```

## 测试

```bash
cp tests/config.php.example tests/config.php
# 填写 ApiAddr、AppId、AppSecret
php7.4 vendor/bin/phpunit tests
```

`tests/` 中包含获取个人实名 URL、上传文档、生成签署链接、查看合同等示例，可对照接口文档使用。

## License

MIT
