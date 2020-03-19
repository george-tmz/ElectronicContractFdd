<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午11:41
 */

namespace georgeT\ElectronicContractFdd\Request;

use Exception;
use georgeT\ElectronicContractFdd\Http\GuzzleTrait;
use georgeT\ElectronicContractFdd\Result\Result;
use GuzzleHttp\Client;
use georgeT\ElectronicContractFdd\Exception\ClientException;
use function GuzzleHttp\Psr7\parse_query;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Exception\GuzzleException;
use function is_array;
use function is_object;
use function json_encode;
use Psr\Http\Message\ResponseInterface;
use function strtolower;
use function strtoupper;

/**
 * Class Request
 * @package georgeT\ElectronicContractFdd\Request
 * @method string resolveParameters()
 */
abstract class Request
{
    use GuzzleTrait;

    /**
     * @var string
     */
    public $scheme = 'http';

    /**
     * @var string
     */
    public $method = 'GET';

    /**
     * @var string
     */
    public $inputFormat = 'XML';

    /**
     * @var string
     */
    public $outputFormat = 'XML';

    /**
     * @var \georgeT\ElectronicContractFdd\Client\Client
     */
    private $client;

    /**
     * @var Uri
     */
    public $uri;

    /**
     * @var Client
     */
    public $guzzle;

    /**
     * @var array The original parameters of the request.
     */
    public $data = [];

    /**
     * Request constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->uri = new Uri();
        $this->uri = $this->uri->withScheme($this->scheme);
        $this->guzzle = new Client();
        $this->options['http_errors'] = FALSE;

        if ($options !== []) {
            $this->options($options);
        }
    }

    /**
     * Set the request data format.
     * @param string $format
     * @return $this
     */
    public function inputFormat(string $format)
    {
        $this->inputFormat = strtoupper($format);

        return $this;
    }

    /**
     * Set the response data format.
     * @param string $format
     * @return $this
     */
    public function outputFormat(string $format)
    {
        $this->outputFormat = strtoupper($format);

        return $this;
    }

    /**
     * Set the request body.
     * @param string $content
     * @return $this
     */
    public function body($content)
    {
        $this->options['body'] = $content;

        return $this;
    }

    /**
     * Set the json as body.
     * @param array|object $content
     * @return $this
     */
    public function jsonBody($content)
    {
        if (is_array($content) || is_object($content)) {
            $content = json_encode($content);
        }

        return $this->body($content);
    }

    /**
     * Set the request scheme.
     * @param string $scheme
     * @return $this
     */
    public function scheme(string $scheme)
    {
        $this->scheme = strtolower($scheme);
        $this->uri = $this->uri->withScheme($this->scheme);

        return $this;
    }

    /**
     * Set the request host.
     * @param string $host
     * @return $this
     */
    public function host(string $host)
    {
        $this->uri = $this->uri->withHost($host);

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function path(string $path)
    {
        $this->uri = $this->uri->withPath($path);

        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function method($method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * @return \georgeT\ElectronicContractFdd\Client\Client
     */
    public function httpClient()
    {
        return $this->client;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        if (isset($this->options['debug'])) {
            return $this->options['debug'] === TRUE;
        }

        return FALSE;
    }

    /**
     * @return Response
     * @throws ClientException
     * @throws Exception
     * @annotation
     */
    public function request()
    {
        $this->resolveParameters();
        if ($this->method == 'GET' && isset($this->options['form_params'])) {
            $this->options['form_params'] = parse_query(
                self::getPostHttpBody($this->options['form_params'])
            );
        }
        $result = new Result($this->response(), $this);

        if (!$result->isSuccess()) {
            //throw new ServerException($result);
            throw new Exception(var_export($result, true));
        }
        return $result->getResponse();
    }

    /**
     * @param array $post
     * @return bool|string
     */
    public static function getPostHttpBody(array $post)
    {
        $content = '';
        foreach ($post as $apiKey => $apiValue) {
            $content .= "$apiKey=" . urlencode($apiValue) . '&';
        }

        return substr($content, 0, -1);
    }

    /**
     * @return mixed|ResponseInterface
     * @throws ClientException
     */
    private function response()
    {
        try {
            return $this->guzzle->request(
                $this->method,
                (string)$this->uri,
                $this->options
            );
        } catch (GuzzleException $e) {
            throw new ClientException($e->getMessage(), 0, $e);
        }
    }
}