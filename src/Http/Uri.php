<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午11:57
 */

namespace georgeT\ElectronicContractFdd\Http;

/**
 * Class Uri
 * @package georgeT\ElectronicContractFdd\Http
 */
class Uri
{
    /**
     * @var string
     */
    private $scheme = '';

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $query = '';

    /**
     * @param string $scheme
     * @return $this
     */
    public function withScheme($scheme)
    {
        $clone = clone $this;
        $clone->scheme = strtolower($scheme);

        return $clone;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function withHost($host)
    {
        $clone = clone $this;
        $clone->host = $host;

        return $clone;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function withPath($path)
    {
        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    /**
     * @param string $query
     * @return $this
     */
    public function withQuery($query)
    {
        $clone = clone $this;
        $clone->query = ltrim($query, '?');

        return $clone;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $url = '';
        if ($this->scheme !== '') {
            $url .= $this->scheme . '://';
        }
        $url .= $this->host;
        if ($this->path !== '') {
            if (isset($this->path[0]) && $this->path[0] !== '/') {
                $url .= '/';
            }
            $url .= $this->path;
        }
        if ($this->query !== '') {
            $url .= '?' . $this->query;
        }

        return $url;
    }
}
