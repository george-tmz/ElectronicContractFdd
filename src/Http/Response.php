<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 下午2:35
 */

namespace georgeT\ElectronicContractFdd\Http;

/**
 * Class Response
 * @package georgeT\ElectronicContractFdd\Http
 */
class Response
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var Stream
     */
    private $body;

    /**
     * Response constructor.
     * @param int    $statusCode
     * @param string $body
     */
    public function __construct($statusCode, $body)
    {
        $this->statusCode = (int)$statusCode;
        $this->body = new Stream($body);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return Stream
     */
    public function getBody()
    {
        return $this->body;
    }
}
