<?php
/**
 * 测试
 * User: george
 * Date: 19-2-28
 * Time: 上午10:35
 */

require __DIR__ . '/vendor/autoload.php';

use georgeT\ElectronicContractFdd\Client\Client;
use georgeT\ElectronicContractFdd\FddServer;

$client = new Client('401744', 'avj5eE2zpzq7joYuv1gwqluN');

$encryptResult = FddServer::encrypt('513722199112025676', 'avj5eE2zpzq7joYuv1gwqluN');

$request = FddServer::rpcRequest($client);

$response = $request->scheme('https')
    ->host('testapi.fadada.com')
    ->path('/api/checkIdCard.api')
    ->method('POST')
    ->inputFormat('json')
    ->outputFormat('json')
    ->options([
        'query' => [
            "app_id"     => '401744',
            "v"          => '2.0',
            "timestamp"  => date('Y-m-d H:s:i'),
            "idCard"     => $encryptResult,
            "name"       => $this->param['name'],
            "msg_digest" => $this->getMsgDigest()
        ]
    ])
;