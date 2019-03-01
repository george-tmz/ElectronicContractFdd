<?php

namespace georgeT\ElectronicContractFdd\Exception;

use georgeT\ElectronicContractFdd\Exception\FddException;
use georgeT\ElectronicContractFdd\Result\Result;

/**
 * Class ServerException
 *
 * @package Exception
 */
class ServerException extends FddException
{
    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var Result
     */
    protected $result;

    /**
     * ServerException constructor.
     *
     * @param Result|null $result
     * @param string      $errorMessage
     * @param string      $errorCode
     */
    public function __construct(Result $result, $errorMessage = '', $errorCode = '')
    {
        $this->result = $result;
        $this->settingProperties();

        if ($errorCode !== '') {
            $this->errorCode = $errorCode;
        }

        if ($errorMessage !== '') {
            $this->errorMessage = $errorMessage;
        }

        if (!$this->errorMessage) {
            $this->errorMessage = (string)$this->result->getResponse()->getBody();
        }

        parent::__construct(
            $this->getMessageString(),
            $this->result->getResponse()->getStatusCode()
        );
    }

    /**
     * Get standard Exception message.
     *
     * @return string
     */
    private function getMessageString()
    {
        $message = "$this->errorCode: $this->errorMessage RequestId: $this->requestId";

        if ($this->getResult()->getRequest()) {
            $method = $this->getResult()->getRequest()->method;
            $uri = (string)$this->getResult()->getRequest()->uri;
            $message .= " $method \"$uri\"";
            if ($this->result->getResponse()) {
                $message .= ' ' . $this->result->getResponse()->getStatusCode();
            }
        }
        return $message;
    }

    /**
     * @return void
     */
    private function settingProperties()
    {
        if (isset($this->result['message'])) {
            $this->errorMessage = $this->result['message'];
            $this->errorCode = $this->result['code'];
        }
        if (isset($this->result['Message'])) {
            $this->errorMessage = $this->result['Message'];
            $this->errorCode = $this->result['Code'];
        }
        if (isset($this->result['errorMsg'])) {
            $this->errorMessage = $this->result['errorMsg'];
            $this->errorCode = $this->result['errorCode'];
        }
        if (isset($this->result['requestId'])) {
            $this->requestId = $this->result['requestId'];
        }
        if (isset($this->result['RequestId'])) {
            $this->requestId = $this->result['RequestId'];
        }
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     * @deprecated deprecated since version 2.0.
     *
     */
    public function getErrorType()
    {
        return 'Server';
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @codeCoverageIgnore
     * @return int
     * @deprecated deprecated since version 2.0.
     *
     */
    public function getHttpStatus()
    {
        return $this->getResult()->getResponse()->getStatusCode();
    }
}