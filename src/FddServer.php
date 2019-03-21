<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 下午3:00
 */

namespace georgeT\ElectronicContractFdd;

use georgeT\ElectronicContractFdd\Config\Config;
use georgeT\ElectronicContractFdd\Encrypt\Crypt3Des;
use georgeT\ElectronicContractFdd\Request\RpcRequest;

/**
 * Class FddServer
 * @package georgeT\ElectronicContractFdd
 */
class FddServer
{
    /**
     * @var string
     */
    public static $appId;

    /**
     * @var string
     */
    public static $appSecret;

    /**
     * @var object
     */

    public static $request;

    /**
     * FddServer constructor.
     * @param string $appId
     * @param string $appSecret
     * @param array  $options
     */
    public function __construct(string $appId, string $appSecret, array $options = [])
    {
        self::$appId     = $appId;
        self::$appSecret = $appSecret;
        self::$request   = new RpcRequest($options);
    }

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
     * @return string
     */
    public static function buildCode32(): string
    {
        $str = date('YmdHis') . str_pad(substr(microtime(TRUE), 11, 4), 4, 0, STR_PAD_RIGHT);
        $str .= self::randomKeys(14);
        if (strlen($str) != 32) {
            self::buildCode32();
        }
        return $str;
    }

    /**
     * 生成小写字母随机字符串
     * @param $length
     * @return string
     */
    public static function randomKeys($length): string
    {
        $output = '';
        for ($a = 0; $a < $length; $a++) {
            $output .= chr(mt_rand(97, 122));
        }
        return $output;
    }

    /**
     * 模板上传
     * @param string $filePath
     * @param string $templateId
     * @return array
     */
    public static function uploadTempToFdd(string $filePath, string $templateId): array
    {
        $appId      = self::$appId;
        $appSecret  = self::$appSecret;
        $timestamp  = date("YmdHis");
        $msg_digest =
            base64_encode(
                strtoupper(
                    sha1($appId .
                        strtoupper(md5($timestamp)) .
                        strtoupper(sha1($appSecret . $templateId))
                    )));
        try {
            $result = self::$request->scheme('https')
                ->host(Config::HOST)
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
                            'contents' => Config::VERSION
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
                            'contents' => fopen($filePath, 'r'),
                        ],
                        [
                            'name'     => 'msg_digest',
                            'contents' => $msg_digest
                        ]
                    ]
                ])->request();
            return [TRUE, $result];
        } catch (\Exception $e) {
            return [FALSE, $e->getMessage()];
        }
    }

    /**
     * 实名认证
     * @param string $customerName
     * @param string $encryptResult
     * @return array
     */
    public static function checkIdCard(string $customerName, string $encryptResult)
    {
        try {
            $timestamp  = date("YmdHis");
            $msg_digest = base64_encode(strtoupper(sha1(self::$appId . strtoupper(md5($timestamp)) . strtoupper(sha1(self::$appSecret)))));

            $response = self::$request->scheme('https')
                ->host(Config::HOST)
                ->path('/api/checkIdCard.api')
                ->method('POST')
                ->inputFormat('array')
                ->outputFormat('json')
                ->options([
                    'query' => [
                        "app_id"     => self::$appId,
                        "v"          => Config::VERSION,
                        "timestamp"  => $timestamp,
                        "idCard"     => $encryptResult,
                        "name"       => $customerName,
                        "msg_digest" => $msg_digest
                    ]
                ])->request();
            return [TRUE, $response];
        } catch (\Exception $e) {
            return [FALSE, $e->getMessage()];
        }
    }

    /**
     * 获取个人CA证书
     * @param string $customerName
     * @param string $encryptResult
     * @param string $email
     * @return array
     */
    public static function applyPersonalCa(string $customerName, string $encryptResult, string $email): array
    {
        try {
            $timestamp  = date("YmdHis");
            $msg_digest =
                base64_encode(
                    strtoupper(
                        sha1(self::$appId .
                            strtoupper(md5($timestamp)) .
                            strtoupper(sha1(self::$appSecret))
                        )));;

            $response = self::$request->scheme('https')
                ->host(Config::HOST)
                ->path('/api/syncPerson_auto.api')
                ->method('POST')
                ->inputFormat('array')
                ->outputFormat('json')
                ->options([
                    'query' => [
                        "app_id"        => self::$appId,
                        "v"             => Config::VERSION,
                        "timestamp"     => $timestamp,
                        "customer_name" => $customerName,
                        "email"         => $email,
                        "ident_type"    => '0',
                        "id_mobile"     => $encryptResult,
                        "msg_digest"    => $msg_digest
                    ]
                ])->request();
            return [TRUE, $response];
        } catch (\Exception $e) {
            return [FALSE, $e->getMessage()];
        }
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
                                         string $fontType = "0"): array
    {
        $timestamp    = date("YmdHis");
        $parameterMap = json_encode($parameterMap);
        $msg_digest   =
            base64_encode(
                strtoupper(
                    sha1(self::$appId .
                        strtoupper(md5($timestamp)) .
                        strtoupper(sha1(self::$appSecret . $templateId . $contractId)) . $parameterMap
                    )));;
        try {
            $response = self::$request->scheme('https')
                ->host(Config::HOST)
                ->path('/api/generate_contract.api')
                ->method('POST')
                ->inputFormat('array')
                ->outputFormat('json')
                ->options([
                    'multipart' => [
                        [
                            'name'     => 'app_id',
                            'contents' => self::$appId
                        ],
                        [
                            'name'     => 'v',
                            'contents' => Config::VERSION
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

    /**
     * 合同推送
     * @param string $contractId
     * @param string $transactionId
     * @param string $customerId
     * @param string $docTitle
     * @param string $signKeyword
     * @param string $returnUrl
     * @param string $pushType
     * @param string $limitType
     * @param string $validity
     * @return array
     */
    public static function pushContract(string $contractId,
                                        string $transactionId,
                                        string $customerId,
                                        string $docTitle,
                                        string $signKeyword,
                                        string $returnUrl,
                                        string $pushType = '1',
                                        string $limitType = '1',
                                        string $validity = '10080'): array
    {
        $timestamp  = date("YmdHis");
        $msg_digest =
            base64_encode(
                strtoupper(
                    sha1(self::$appId .
                        strtoupper(md5($timestamp)) .
                        strtoupper(sha1(self::$appSecret . $contractId . $transactionId . $pushType . $customerId . $signKeyword))
                    )));;
        try {
            $response = self::$request->scheme('https')
                ->host(Config::HOST)
                ->path('/api/pushdoc_extsign.api')
                ->method('POST')
                ->inputFormat('array')
                ->outputFormat('json')
                ->options([
                    'query' => [
                        "app_id"         => self::$appId,
                        "timestamp"      => $timestamp,
                        "v"              => Config::VERSION,
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