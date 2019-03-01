<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-2-28
 * Time: 上午11:54
 */

namespace georgeT\ElectronicContractFdd\Exception;

use Throwable;

/**
 * Class ClientException
 * @package georgeT\ElectronicContractFdd\Exception
 */
class ClientException extends FddException
{
    /**
     * ClientException constructor.
     * @param string         $message
     * @param int            $code
     * @param Throwable|NULL $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
        $this->errorMessage = $message;
        $this->errorCode    = $code;
    }

    /**
     * @return string
     */
    public function getErrorType()
    {
        return 'Client';
    }
}