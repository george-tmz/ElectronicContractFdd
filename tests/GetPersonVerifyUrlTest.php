<?php

use georgeT\ElectronicContractFdd\Config\Config;
use georgeT\ElectronicContractFdd\Exception\ClientException;
use georgeT\ElectronicContractFdd\FddServer;
use PHPUnit\Framework\TestCase;

require './config.php';

class GetPersonVerifyUrlTest extends TestCase
{
    /**
     * @throws ClientException
     */
    public function testUpload()
    {
        $fddServer = new FddServer(AppId, AppSecret);
        $customerId = '';
        $returnUrl = 'https://www.baidu.com';
        $param = [
            'customer_id'         => $customerId,
            'verified_way'        => '0',
            'page_modify'         => '1',
            'return_url'          => $returnUrl,
            'customer_name'       => '何中碧',
            'customer_ident_type' => '0',
            'customer_ident_no'   => '',
            'mobile'              => '',
            'ident_front_path'    => '',
            'result_type'         => '1',
            'cert_flag'           => '1',
        ];
        $msgDigest = $fddServer->buildMsgDigest($param);
        $requestParam = array_merge([
            "app_id"     => $fddServer->appId,
            "timestamp"  => $fddServer->timestamp,
            "v"          => Config::VERSION,
            "msg_digest" => $msgDigest
        ], $param);
        $result = $fddServer->request->scheme('https')
            ->host(ApiAddr)
            ->path('/get_person_verify_url.api')
            ->method('POST')
            ->inputFormat('array')
            ->outputFormat('json')
            ->options([
                'query' => $requestParam
            ])->request();
        $this->assertEquals(true, true);
        $this->assertEquals($result['code'], 1);
        var_dump($result['data']);
        echo base64_decode($result['data']['url']);
    }
}