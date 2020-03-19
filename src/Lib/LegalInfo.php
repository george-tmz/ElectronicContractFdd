<?php

namespace georgeT\ElectronicContractFdd\Lib;

/**
 * Class LegalInfo
 * @package georgeT\ElectronicContractFdd\Lib
 * @author George
 * @date 2020/3/19
 * @time 10:53
 */
class LegalInfo
{
    /**
     * @var string 法人姓名
     */
    protected $legalName = '';

    /**
     * @var string 法人证件号(身份证)
     */
    protected $legalId = '';

    /**
     * @var string 法人手机号(仅支持国内运营商)
     */
    protected $legalMobile = '';

    /**
     * @var string 法人证件正面照下载地址
     */
    protected $legalIdFrontPath = '';

    /**
     * @param $legalName
     * @annotation
     */
    public function setLegalName(string $legalName)
    {
        $this->legalName = $legalName;
    }

    /**
     * @param $legalId
     * @annotation
     */
    public function setLegalId(string $legalId)
    {
        $this->legalId = $legalId;
    }

    /**
     * @param $legalMobile
     * @annotation
     */
    public function setLegalMobile(string $legalMobile)
    {
        $this->legalMobile = $legalMobile;
    }

    /**
     * @param $legalIdFrontPath
     * @annotation
     */
    public function setLegalIdFrontPath(string $legalIdFrontPath)
    {
        $this->legalIdFrontPath = $legalIdFrontPath;
    }
}
