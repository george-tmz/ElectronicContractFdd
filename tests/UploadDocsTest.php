<?php

use georgeT\ElectronicContractFdd\Config\Config;
use georgeT\ElectronicContractFdd\Exception\ClientException;
use georgeT\ElectronicContractFdd\FddServer;
use PHPUnit\Framework\TestCase;

require './config.php';

class UploadDocsTest extends TestCase
{
    /**
     * @throws ClientException
     */
    public function testUpload()
    {
        $fddServer = new FddServer(AppId, AppSecret);
        $contractId = date('Ymd001');
        $msgDigest = $fddServer->buildMsgDigest([$contractId]);
        $result = $fddServer->request->scheme('https')
            ->host(ApiAddr)
            ->path('/api/uploaddocs.api')
            ->method('POST')
            ->inputFormat('array')
            ->outputFormat('json')
            ->options([
                'multipart' => [
                    [
                        'name'     => 'app_id',
                        'contents' => $fddServer->appId
                    ],
                    [
                        'name'     => 'timestamp',
                        'contents' => $fddServer->timestamp
                    ],
                    [
                        'name'     => 'v',
                        'contents' => Config::VERSION,
                    ],
                    [
                        'name'     => 'msg_digest',
                        'contents' => $msgDigest
                    ],
                    [
                        'name'     => 'contract_id',
                        'contents' => $contractId,
                    ],
                    [
                        'name'     => 'doc_title',
                        'contents' => 'wm劳动合同-test',
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen('/tmp/277568.pdf', 'r'),
                    ],
                    [
                        'name'     => 'doc_type',
                        'contents' => '.pdf',
                    ],
                ]
            ])
            ->request();
        var_dump($result);
        $this->assertEquals($result['result'], 'success');
    }
}