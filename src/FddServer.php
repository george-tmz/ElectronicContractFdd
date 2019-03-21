<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 下午3:00
 */

namespace georgeT\ElectronicContractFdd;
use georgeT\ElectronicContractFdd\Encrypt\Crypt3Des;
use georgeT\ElectronicContractFdd\Traits\RequestTrait;

/**
 * Class FddServer
 * @package georgeT\ElectronicContractFdd
 * @mixin RequestTrait
 */
class FddServer
{
    use RequestTrait;
    /**
     * @param $data
     * @param $key
     * @return array
     */
    public static function encrypt($data, $key)
    {
        return Crypt3Des::encrypt($data, $key);
    }
    /**
     * 构建合同
     * @param array  $parameterMap
     * @param string $templateId
     * @param string $contractId
     * @param string $docTitle
     * @param string $fontSize
     * @param string $fontType
     * @return array
     */
    public static function buildContract(array $parameterMap,
                                   string $templateId,
                                   string $contractId,
                                   string $docTitle,
                                   string $fontSize = "12",
                                   string $fontType = "0")
    {
        $appId        = self::$appId;
        $appSecret    = self::$appSecret;
        $timestamp    = date("YmdHis");
        $parameterMap = json_encode($parameterMap);
        $msg_digest   =
            base64_encode(
                strtoupper(
                    sha1($appId .
                        strtoupper(md5($timestamp)) .
                        strtoupper(sha1($appSecret . $templateId . $contractId)) . $parameterMap
                    )));;
        try {
            $request  = FddServer::rpcRequest();
            $response = $request->scheme('https')
                ->host(config('fadada.HOST'))
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
                            'contents' => config('fadada.VERSION')
                        ],
                        [
                            'name'     => 'timestamp',
                            'contents' => $timestamp
                        ],
                        [
                            'name'     => 'doc_title',
                            'contents' => $docTitle
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
                            'contents' => $fontSize
                        ],
                        [
                            'name'     => 'font_type',
                            'contents' => $fontType
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
            return [TRUE, $response];
        } catch (\Exception $e) {
            return [FALSE, $e->getMessage()];
        }
    }

    public static function pushContract(string $contractId,
                                  string $transactionId,
                                  string $customerId,
                                  string $docTitle,
                                  string $signKeyword,
                                  string $returnUrl,
                                  string $pushType = '1',
                                  string $limitType = '1',
                                  string $validity = '10080')
    {
        $appId        = self::$appId;
        $appSecret    = self::$appSecret;
        $timestamp  = date("YmdHis");
        $msg_digest =
            base64_encode(
                strtoupper(
                    sha1($appId .
                        strtoupper(md5($timestamp)) .
                        strtoupper(sha1($appSecret . $contractId . $transactionId . $pushType . $customerId . $signKeyword))
                    )));;
        try {
            $request  = FddServer::rpcRequest();
            $response = $request->scheme('https')
                ->host(config('fadada.HOST'))
                ->path('/api/pushdoc_extsign.api')
                ->method('POST')
                ->inputFormat('array')
                ->outputFormat('json')
                ->options([
                    'query' => [
                        "app_id"         => $appId,
                        "timestamp"      => $timestamp,
                        "v"              => config('fadada.HOST'),
                        'push_type'      => $pushType,
                        'transaction_id' => $transactionId,
                        'contract_id'    => $contractId,
                        'customer_id'    => $customerId,
                        'doc_title'      => $docTitle,
                        'sign_keyword'   => $signKeyword,
                        'limit_type'     => $limitType,
                        'validity'       => $validity,
                        'return_url'     => $returnUrl,
                        'msg_digest'     => $msg_digest
                    ]
                ])->request();
            return [TRUE, $response];
        } catch (\Exception $e) {
            return [FALSE, $e->getMessage()];
        }
    }
}