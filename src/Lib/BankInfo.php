<?php

namespace georgeT\ElectronicContractFdd\Lib;
/**
 * Class BankInfo
 * @package georgeT\ElectronicContractFdd\Lib
 * @author George
 * @date 2020/3/19
 * @time 11:27
 */
class BankInfo
{
    /**
     * @var string 银行名称
     */
    protected $bankName = '';
    /**
     * @var string
     */
    protected $bankId = '';
    /**
     * @var string
     */
    protected $subbranchName = '';

    /**
     * @param string $bankName
     * @annotation
     */
    public function setBankName(string $bankName)
    {
        $this->bankName = $bankName;
    }

    /**
     * @param string $bankId
     * @annotation
     */
    public function setBankId(string $bankId)
    {
        $this->bankId = $bankId;
    }

    /**
     * @param string $subbranchName
     * @annotation
     */
    public function setSubbranchName(string $subbranchName)
    {
        $this->subbranchName = $subbranchName;
    }
}