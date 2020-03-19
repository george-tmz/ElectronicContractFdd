<?php

namespace georgeT\ElectronicContractFdd\Lib;
/**
 * Class CompanyInfo
 * @package georgeT\ElectronicContractFdd\Lib
 * @author George
 * @date 2020/3/19
 * @time 11:24
 */
class CompanyInfo
{
    /**
     * @var string 企业名称
     */
    protected $companyInfo = '';

    /**
     * @var string 统一社会信用代码
     */
    protected $creditNo = '';

    /**
     * @var string
     */
    protected $creditImagePath = '';

    /**
     * @param string $companyInfo
     * @annotation
     */
    public function setCompanyInfo(string $companyInfo)
    {
        $this->companyInfo = $companyInfo;
    }

    /**
     * @param string $creditNo
     * @annotation
     */
    public function setCreditNo(string $creditNo)
    {
        $this->creditNo = $creditNo;
    }

    /**
     * @param string $creditImagePath
     * @annotation
     */
    public function setCreditImagePath(string $creditImagePath)
    {
        $this->creditImagePath = $creditImagePath;
    }
}