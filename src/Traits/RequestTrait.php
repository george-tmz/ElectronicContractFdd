<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午11:33
 */

namespace georgeT\ElectronicContractFdd\Traits;


use georgeT\ElectronicContractFdd\Client\Client;
use georgeT\ElectronicContractFdd\Request\RpcRequest;

/**
 * Class RequestTrait
 * @package georgeT\ElectronicContractFdd\Traits
 */
trait RequestTrait
{
    /**
     * @param Client $client
     * @param array  $options
     * @return RpcRequest
     */
    public static function rpcRequest(Client $client, array $options = [])
    {
        return new RpcRequest($client, $options);
    }
}