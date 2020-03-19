<?php

namespace georgeT\ElectronicContractFdd\Lib;
/**
 * Class AgentInfo
 * @package georgeT\ElectronicContractFdd\Lib
 * @author George
 * @date 2020/3/19
 * @time 10:56
 */
class AgentInfo
{
    /**
     * @var string 代理人姓名
     */
    protected $agentName = '';

    /**
     * @var string 代理人证件号(身份证)
     */
    protected $agentId = '';

    /**
     * @var string 代理人手机号(仅支持国内运营商)
     */
    protected $agentMobile = '';

    /**
     * @var string 代理人证件正面照下载地址
     */
    protected $agentIdFrontPath = '';

    /**
     * @param $agentName
     * @annotation
     */
    public function setAgentName(string $agentName)
    {
        $this->agentName = $agentName;
    }

    /**
     * @param $agentId
     * @annotation
     */
    public function setAgentId(string $agentId)
    {
        $this->agentId = $agentId;
    }

    /**
     * @param $agentMobile
     * @annotation
     */
    public function setAgentMobile(string $agentMobile)
    {
        $this->agentMobile = $agentMobile;
    }

    /**
     * @param $agentIdFrontPath
     * @annotation
     */
    public function setAgentIdFrontPath(string $agentIdFrontPath)
    {
        $this->agentIdFrontPath = $agentIdFrontPath;
    }
}