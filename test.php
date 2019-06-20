<?php
//echo 123;die;
use georgeT\ElectronicContractFdd\Config\Config;
use georgeT\ElectronicContractFdd\FddServer;
use georgeT\ElectronicContractFdd\Request\RpcRequest;

require __DIR__ . "/vendor/autoload.php";

$appInfo = [
    'APP_ID'          => '',
    'APP_SECRET'      => '',
    'HPW_CUSTOMER_ID' => ''
];

/*$param = [
    'open_id'      => 'ovwxwlfwgisnuadwnnvgvseutjicqfav',
    'account_type' => '1'
];*/
$param = [
    'customer_id'         => 'C6EE466583E3D6B66F858894176A6AF0',
    'verified_way'        => '0',
    'page_modify'         => '1',
    'notify_url'          => '',
    'return_url'          => '',
    'customer_name'       => '滕明志',
    'customer_ident_type' => '0',
    'customer_ident_no'   => '',
    'mobile'              => '0',
    'result_type'         => '1',
    'cert_fla'            => '1'
];

$fddServer = new FddServer($appInfo['APP_ID'], $appInfo['APP_SECRET']);
try {
    $msgDigest    = $fddServer::buildMsgDigest($param);
    $requestParam = [
        "app_id"     => $fddServer::$appId,
        "timestamp"  => $fddServer::$timestamp,
        "v"          => Config::VERSION,
        "msg_digest" => $msgDigest
    ];
    foreach ($param as $key => $value) {
        $requestParam[$key] = $value;
    }
    $result = $fddServer::$request->scheme('https')
        ->host(Config::HOST)
        ->path('/api/account_register.api')
        ->method('POST')
        ->inputFormat('array')
        ->outputFormat('json')
        ->options([
                      'query' => $requestParam
                  ])->request();
    var_dump($result);
} catch (Exception $exception) {
    var_dump($exception->getMessage());
}



