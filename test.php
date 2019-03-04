<?php
/**
 * 测试
 * User: george
 * Date: 19-2-28
 * Time: 上午10:35
 */

require __DIR__ . '/vendor/autoload.php';

use georgeT\ElectronicContractFdd\FddServer;

//实名认证
/*list($bool, $encryptResult) = FddServer::encrypt('513722199112025676', 'avj5eE2zpzq7joYuv1gwqluN');
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
}*/
//获取CA证书
/*list($bool, $encryptResult) = FddServer::encrypt('513722199112025676|18123419987', 'avj5eE2zpzq7joYuv1gwqluN');
$request    = FddServer::rpcRequest();
$timestamp  = date("YmdHis");
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
        ->path('/api/syncPerson_auto.api')
        ->method('POST')
        ->inputFormat('array')
        ->outputFormat('json')
        ->options([
            'query' => [
                "app_id"        => '401744',
                "v"             => '2.0',
                "timestamp"     => $timestamp,
                "customer_name" => '滕明志',
                "email"         => 'cdzx-tmz@hpw.com.cn',
                "ident_type"    => '0',
                "id_mobile"     => $encryptResult,
                "msg_digest"    => $msg_digest
            ]
        ])->request();
    var_dump($response);
} catch (\Exception $e) {
    return [FALSE, $e->getMessage()];
}*/
//上传模板
//echo __DIR__;die;
//var_dump(fopen('./aabb.pdf', 'r'));die;
$timestamp  = date("YmdHis");
$appId      = "401744";
$appSecret  = "avj5eE2zpzq7joYuv1gwqluN";
$templateId = "itT7C4qfJ4A6msnchE7KErZ7sz8NZh9c";
/*$msg_digest   =
    base64_encode(
        strtoupper(
            sha1($appId .
                strtoupper(md5($timestamp)) .
                strtoupper(sha1($appSecret . $templateId))
            )));;

try {
    $request  = FddServer::rpcRequest();
    $response = $request->scheme('https')
        ->host('testapi.fadada.com')
        ->path('/api/uploadtemplate.api')
        ->method('POST')
        ->inputFormat('array')
        ->outputFormat('json')
        ->options([
            'multipart' => [
                [
                    'name'     => 'app_id',
                    'contents' => $appId
                ],
                [
                    'name'     => 'v',
                    'contents' => '2.0'
                ],
                [
                    'name'     => 'timestamp',
                    'contents' => $timestamp
                ],
                [
                    'name'     => 'template_id',
                    'contents' => $templateId
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen('./aabb.pdf', 'r'),
                ],
                [
                    'name'     => 'msg_digest',
                    'contents' => $msg_digest
                ]
            ]
        ])->request();
    var_dump($response);
} catch (\Exception $e) {
    return [FALSE, $e->getMessage()];
}*/
//合同生成合同
$contractId    = 'zYGAiUU2816Raiw0EcVCbLYyeLVcQP5v';
$parameterMap  = json_encode(['platformName' => '海霸王', 'borrower' => 'Boss Horse']);
$transactionId = 'I24cSgoHKj5dTMv0pSlLR4JneDOlNRKC';
$customerId    = '68256E409549330D3DD7E52BC18835DC';
//var_dump($parameterMap);die;
/*$msg_digest =
    base64_encode(
        strtoupper(
            sha1($appId .
                strtoupper(md5($timestamp)) .
                strtoupper(sha1($appSecret . $templateId . $contractId)) . $parameterMap
            )));;*/
/*try {
    $request  = FddServer::rpcRequest();
    $response = $request->scheme('https')
        ->host('testapi.fadada.com')
        ->path('/api/generate_contract.api')
        ->method('POST')
        ->inputFormat('array')
        ->outputFormat('json')
        ->options([
            'multipart' => [
                [
                    'name'     => 'app_id',
                    'contents' => $appId
                ],
                [
                    'name'     => 'v',
                    'contents' => '2.0'
                ],
                [
                    'name'     => 'timestamp',
                    'contents' => $timestamp
                ],
                [
                    'name'     => 'doc_title',
                    'contents' => "测试合同"
                ],
                [
                    'name'     => 'template_id',
                    'contents' => $templateId
                ],
                [
                    'name'     => 'contract_id',
                    'contents' => $contractId
                ],
                [
                    'name'     => 'font_size',
                    'contents' => "9"
                ],
                [
                    'name'     => 'font_type',
                    'contents' => '0'
                ],
                [
                    'name'     => 'parameter_map',
                    'contents' => $parameterMap,
                ],
                [
                    'name'     => 'msg_digest',
                    'contents' => $msg_digest
                ]
            ]
        ])->request();
    var_dump($response);
} catch (\Exception $e) {
    return [FALSE, $e->getMessage()];
}*/
//合同推送接口
$msg_digest =
    base64_encode(
        strtoupper(
            sha1($appId .
                strtoupper(md5($timestamp)) .
                strtoupper(sha1($appSecret . $contractId . $transactionId . '1' . $customerId . '委托方签字'))
            )));;
try {
    $request  = FddServer::rpcRequest();
    $response = $request->scheme('https')
        ->host('testapi.fadada.com')
        ->path('/api/pushdoc_extsign.api')
        ->method('POST')
        ->inputFormat('array')
        ->outputFormat('json')
        ->options([
            'query' => [
                "app_id"         => $appId,
                "timestamp"      => $timestamp,
                "v"              => '2.0',
                'push_type'      => '1',
                'transaction_id' => $transactionId,
                'contract_id'    => $contractId,
                'customer_id'    => $customerId,
                'doc_title'      => "测试合同",
                'sign_keyword'   => '委托方签字',
                'limit_type'     => "1",
                'validity'       => "10080",
                'return_url'     => "http://wx.api.stlswm.com/return.php",
                'msg_digest'     => $msg_digest
            ]
        ])->request();
    var_dump($response);
} catch (\Exception $e) {
    return [FALSE, $e->getMessage()];
}