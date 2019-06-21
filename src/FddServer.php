<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 下午3:00
 */

namespace georgeT\ElectronicContractFdd;

use Exception;
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

    public static $timestamp;

    /**
     * FddServer constructor.
     * @param string $appId
     * @param string $appSecret
     * @param array  $options
     */
    public function __construct(string $appId, string $appSecret, array $options = [])
    {
        self::$timestamp = date("YmdHis");
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
     * @param array $param
     * @return string
     * @throws Exception
     * @annotation
     */
    public static function buildMsgDigest(array $param)
    {
        if (empty($param)) {
            throw new Exception('无业务参数');
        }
        ksort($param);
        $strParam = implode('', $param);
        return base64_encode(
            strtoupper(sha1(
                           self::$appId .
                           strtoupper(md5(self::$timestamp)) .
                           strtoupper(sha1(self::$appSecret . $strParam))
                       )));
    }
}