<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 下午3:00
 */

namespace georgeT\ElectronicContractFdd;

use Exception;
use georgeT\ElectronicContractFdd\Encrypt\Crypt3Des;
use georgeT\ElectronicContractFdd\Lib\AgentInfo;
use georgeT\ElectronicContractFdd\Lib\BankInfo;
use georgeT\ElectronicContractFdd\Lib\CompanyInfo;
use georgeT\ElectronicContractFdd\Lib\LegalInfo;
use georgeT\ElectronicContractFdd\Request\RpcRequest;

/**
 * Class FddServer
 * @package georgeT\ElectronicContractFdd
 */
class FddServer
{
    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appSecret;

    /**
     * @var object
     */

    public $request;

    public $timestamp;

    /**
     * FddServer constructor.
     * @param string $appId
     * @param string $appSecret
     * @param array $options
     */
    public function __construct(string $appId, string $appSecret, array $options = [])
    {
        $this->timestamp = date("YmdHis");
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->request = new RpcRequest($options);
    }

    /**
     * @param $data
     * @param $key
     * @return array
     */
    public function encrypt($data, $key)
    {
        return Crypt3Des::encrypt($data, $key);
    }

    /**
     * @return string
     */
    public function buildCode32(): string
    {
        $str = date('YmdHis') . str_pad(substr(microtime(TRUE), 11, 4), 4, 0, STR_PAD_RIGHT);
        $str .= self::randomKeys(14);
        if (strlen($str) != 32) {
            self::buildCode32();
        }
        return $str;
    }

    /**
     * 生成小写字母随机字符串
     * @param $length
     * @return string
     */
    public function randomKeys($length): string
    {
        $output = '';
        for ($a = 0; $a < $length; $a++) {
            $output .= chr(mt_rand(97, 122));
        }
        return $output;
    }

    /**
     * @param array $param
     * @return string
     * @throws Exception
     * @annotation
     */
    public function buildMsgDigest(array $param)
    {
        if (empty($param)) {
            throw new Exception('无业务参数');
        }
        ksort($param);
        $strParam = implode('', $param);
        return base64_encode(
            strtoupper(sha1(
                $this->appId .
                strtoupper(md5($this->timestamp)) .
                strtoupper(sha1($this->appSecret . $strParam))
            )));
    }

    /**
     * @param string $bankName
     * @param string $bankId
     * @param string $subbranchName
     * @return BankInfo
     * @annotation
     */
    public function bankInfo(string $bankName, string $bankId, string $subbranchName)
    {
        $bankInfo = new BankInfo();
        $bankInfo->setBankName($bankName);
        $bankInfo->setBankId($bankId);
        $bankInfo->setSubbranchName($subbranchName);
        return $bankInfo;
    }

    /**
     * @param string $companyName
     * @param string $creditNo
     * @param string $creditImagePath
     * @return CompanyInfo
     * @annotation
     */
    public function companyInfo(string $companyName, string $creditNo, string $creditImagePath)
    {
        $companyInfo = new CompanyInfo();
        $companyInfo->setCompanyName($companyName);
        $companyInfo->setCreditNo($creditNo);
        $companyInfo->setCreditImagePath($creditImagePath);
        return $companyInfo;
    }

    /**
     * @param string $legalName
     * @param string $legalId
     * @param string $legalMobile
     * @param string $legalIdFrontPath
     * @return string
     * @annotation
     */
    public function legalInfo(string $legalName, string $legalId, string $legalMobile, string $legalIdFrontPath)
    {
        $legalInfo = new LegalInfo();
        $legalInfo->setLegalName($legalName);
        $legalInfo->setLegalId($legalId);
        $legalInfo->setLegalMobile($legalMobile);
        $legalInfo->setLegalIdFrontPath($legalIdFrontPath);
        return $legalIdFrontPath;
    }

    /**
     * @param string $agentName
     * @param string $agentId
     * @param string $agentMobile
     * @param string $agentIdFrontPath
     * @return AgentInfo
     * @annotation
     */
    public function agentInfo(string $agentName, string $agentId, string $agentMobile, string $agentIdFrontPath)
    {
        $agentInfo = new AgentInfo();
        $agentInfo->setAgentName($agentName);
        $agentInfo->setAgentId($agentId);
        $agentInfo->setAgentMobile($agentMobile);
        $agentInfo->setAgentIdFrontPath($agentIdFrontPath);
        return $agentInfo;
    }
}