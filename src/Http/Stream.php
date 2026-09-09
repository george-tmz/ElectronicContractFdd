<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 下午2:35
 */

namespace georgeT\ElectronicContractFdd\Http;

/**
 * Class Stream
 * @package georgeT\ElectronicContractFdd\Http
 */
class Stream
{
    /**
     * @var string
     */
    private $contents;

    /**
     * Stream constructor.
     * @param string $contents
     */
    public function __construct($contents)
    {
        $this->contents = (string)$contents;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->contents;
    }
}
