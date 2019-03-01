<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午11:33
 */

namespace georgeT\ElectronicContractFdd\Traits;


use georgeT\ElectronicContractFdd\Client\Client;

/**
 * Class RequestTrait
 * @package georgeT\ElectronicContractFdd\Traits
 */
class RequestTrait
{
    /**
     * @param $name
     * @param $value
     */
    /*public static function appendUserAgent($name, $value)
    {
        UserAgent::append($name, $value);
    }*/

    /**
     * @param array $userAgent
     */
    /*public static function withUserAgent(array $userAgent)
    {
        UserAgent::with($userAgent);
    }*/

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