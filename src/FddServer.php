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
}