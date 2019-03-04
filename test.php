<?php
/**
 * 测试
 * User: george
 * Date: 19-2-28
 * Time: 上午10:35
 */

require __DIR__ . '/vendor/autoload.php';

use georgeT\ElectronicContractFdd\FddServer;

list($bool, $encryptResult) = FddServer::encrypt('513722199112025676', 'avj5eE2zpzq7joYuv1gwqluN');
$request = FddServer::rpcRequest();
$timestamp = date("YmdHis");
$msg_digest =
    base64_encode(
        strtoupper(
            sha1('401744' .
                strtoupper(md5($timestamp)) .
                strtoupper(sha1('avj5eE2zpzq7joYuv1gwqluN'))
            )));;
try {
    $response = $request->scheme('https')
        ->host('testapi.fadada.com')
        ->path('/api/checkIdCard.api')
        ->method('POST')
        ->inputFormat('array')
        ->outputFormat('json')
        ->options([
            'query' => [
                "app_id"     => '401744',
                "v"          => '2.0',
                "timestamp"  => $timestamp,
                "idCard"     => $encryptResult,
                "name"       => '滕明志',
                "msg_digest" => $msg_digest
            ]
        ])->request();
    var_dump($response);
} catch (\Exception $e) {
    return [FALSE, $e->getMessage()];
}
