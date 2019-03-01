<?php

namespace georgeT\ElectronicContractFdd\Client;
/**
 * Class Client
 */
class Client
{
    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appSecret;

    /**
     * Client constructor.
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct(string $appId, string $appSecret)
    {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;
    }
}