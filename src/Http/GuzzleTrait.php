<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午11:57
 */

namespace georgeT\ElectronicContractFdd\Http;

trait GuzzleTrait
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @param $timeout
     * @return $this
     */
    public function timeout($timeout)
    {
        $this->options['timeout'] = $timeout;
        return $this;
    }

    /**
     * @param $connectTimeout
     * @return $this
     */
    public function connectTimeout($connectTimeout)
    {
        $this->options['connect_timeout'] = $connectTimeout;
        return $this;
    }

    /**
     * @param $debug
     * @return $this
     */
    public function debug($debug)
    {
        $this->options['debug'] = $debug;
        return $this;
    }

    /**
     * @param $cert
     * @return $this
     */
    public function cert($cert)
    {
        $this->options['cert'] = $cert;
        return $this;
    }

    /**
     * @param $proxy
     * @return $this
     */
    public function proxy($proxy)
    {
        $this->options['proxy'] = $proxy;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function options(array $options)
    {
        if ($options !== []) {
            $this->options = array_merge($this->options, $options);
        }
        return $this;
    }
}