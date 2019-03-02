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
     * @param array $options
     * @return RpcRequest
     */
    public static function rpcRequest(array $options = [])
    {
        return new RpcRequest($options);
    }
}