<?php

use georgeT\ElectronicContractFdd\Config\Config;
use georgeT\ElectronicContractFdd\Exception\ClientException;
use georgeT\ElectronicContractFdd\FddServer;
use PHPUnit\Framework\TestCase;

require './config.php';

class ExtSignTest extends TestCase
{
    /**
     * @throws ClientException
     */
    public function testUpload()
    {
        $fddServer = new FddServer(AppId, AppSecret);
        $transactionId = date('YmdHis');
        var_dump($transactionId);
        $customerId = '107C57D6B5743F6AF21EEA0EF699D18A';
        $contractId = date('Ymd001');
        $msgDigest = base64_encode(
            strtoupper(sha1(
                $fddServer->appId.
                strtoupper(md5($transactionId.$fddServer->timestamp)).
                strtoupper(sha1($fddServer->appSecret.$customerId))
            )));
        $requestParam = [
            "app_id"           => $fddServer->appId,
            "timestamp"        => $fddServer->timestamp,
            "v"                => Config::VERSION,
            "msg_digest"       => $msgDigest,
            'transaction_id'   => $transactionId,
            'contract_id'      => $contractId,
            'customer_id'      => $customerId,
            'doc_title'        => '劳动合同',
            'sign_keyword'     => '签字或盖章',
            'keyword_strategy' => '0',
            'return_url'       => 'https://www.baidu.com'
        ];
        $url = 'https://'.ApiAddr.'api/extsign.api?'.http_build_query($requestParam);
        echo $url;
        $this->assertEquals(true, true);
    }
}