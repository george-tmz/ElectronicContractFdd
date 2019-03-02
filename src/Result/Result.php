<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 下午2:35
 */

namespace georgeT\ElectronicContractFdd\Result;

use \georgeT\ElectronicContractFdd\Traits\DataTransferTrait;
use \georgeT\ElectronicContractFdd\Request\Request;
use GuzzleHttp\Psr7\Response;

class Result
{
    use DataTransferTrait;

    /**
     * Instance of the response.
     * @var Response
     */
    protected $response;

    /**
     * Instance of the request.
     * @var Request
     */
    protected $request;

    /**
     * Result constructor.
     * @param Response $response
     * @param Request  $request
     */
    public function __construct(Response $response, Request $request = NULL)
    {
        $format = ($request instanceof Request) ? \strtoupper($request->outputFormat) : 'XML';

        switch ($format) {
            case 'JSON':
                $response = self::jsonToArray($response->getBody()->getContents());
                break;
            case 'XML':
                $response = self::xmlToArray($response->getBody()->getContents());
                break;
            case 'RAW':
                $response = self::jsonToArray($response->getBody()->getContents());
                break;
            default:
                $response = self::jsonToArray($response->getBody()->getContents());
        }
        $this->response = $response;
        $this->request  = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        var_dump($this->response);die;
        return 200 <= $this->response->getStatusCode()
            && 300 > $this->response->getStatusCode();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->response->getBody();
    }
}