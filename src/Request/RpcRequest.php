<?php

namespace georgeT\ElectronicContractFdd\Request;

use georgeT\ElectronicContractFdd\Traits\DataTransferTrait;

/**
 * Class RpcRequest
 * @package georgeT\ElectronicContractFdd\Request
 */
class RpcRequest extends Request
{
    use DataTransferTrait;
    /**
     * @var string
     */
    private $dateTimeFormat = 'Y-m-d\TH:i:s\Z';

    /**
     * @return string|void
     */
    public function resolveParameters()
    {
        if ($this->method === 'POST') {
            switch ($this->inputFormat) {
                case 'XML':
                    $this->body(self::arrayToXml($this->options['query']));
                    break;
                case 'JSON':
                    $this->jsonBody($this->options['query']);
                    break;
                default:
                    if (!isset($this->options['multipart'])) {
                        foreach ($this->options['query'] as $apiParamKey => $apiParamValue) {
                            $this->options['form_params'][$apiParamKey] = $apiParamValue;
                        }
                    }
            }
            unset($this->options['query']);
        }
    }
}
