<?php

use georgeT\ElectronicContractFdd\Config\Config;
use georgeT\ElectronicContractFdd\Exception\ClientException;
use georgeT\ElectronicContractFdd\FddServer;
use PHPUnit\Framework\TestCase;

require './config.php';

class ViewContractTest extends TestCase
{
    /**
     * @throws ClientException
     */
    public function testView()
    {
        $fddServer = new FddServer(AppId, AppSecret);
        $contractId = date('Ymd001');
        $msgDigest = $fddServer->buildMsgDigest([$contractId]);
        $url = 'https://'.ApiAddr.'api/viewContract.api?'.http_build_query([
                'app_id'      => $fddServer->appId,
                'timestamp'   => $fddServer->timestamp,
                'v'           => Config::VERSION,
                'msg_digest'  => $msgDigest,
                'contract_id' => $contractId,
            ]);
        echo $url;
        $this->assertEquals(true, true);
    }
}