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
        $contractId = date('YmdHis');
        $body = [
            'contract_id' => $contractId,
            'doc_title'   => 'wangmin test',
            'doc_type'    => '.pdf',
        ];
        $msgDigest = $fddServer->buildMsgDigest($body);
        $body['file'] = fopen('/tmp/277568.pdf', 'r');
        $multipart = [
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
        ];
        foreach ($body as $key => $item) {
            $multipart[] = [
                'name'     => $key,
                'contents' => $item
            ];
        }
        $res = $fddServer->request->scheme('http')
            ->host(ApiAddr)
            ->path('/api/uploaddocs.api')
            ->method('POST')
            ->inputFormat('array')
            ->outputFormat('json')
            ->options([
                'multipart' => $multipart
            ])
            ->request();
        var_dump($res);
        die;
    }
}