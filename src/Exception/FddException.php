<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-2-28
 * Time: 上午11:34
 */
namespace georgeT\ElectronicContractFdd\Exception;

/**
 * Class FddException
 * @package georgeT\ElectronicContractFdd\Exception
 */
class FddException extends \Exception
{
    /**
     * @var string
     */
    protected $errorCode;
    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function setErrorType(){}
}
