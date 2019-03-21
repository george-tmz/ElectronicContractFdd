<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午11:33
 */

namespace georgeT\ElectronicContractFdd\Traits;

use georgeT\ElectronicContractFdd\Request\RpcRequest;

/**
 * Class RequestTrait
 * @package georgeT\ElectronicContractFdd\Traits
 */
trait RequestTrait
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
     * @param array $options
     * @return RpcRequest
     */
    public static function rpcRequest(string $appId, string $appSecret, array $options = [])
    {
        self::$appId     = $appId;
        self::$appSecret = $appSecret;
        return new RpcRequest($options);
    }
}