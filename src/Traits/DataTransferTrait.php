<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午10:38
 */

namespace georgeT\ElectronicContractFdd\Traits;

/**
 * Trait DataTransferTrait
 * @package georgeT\ElectronicContractFdd\Traits
 */
trait DataTransferTrait
{
    /**
     * @param array $data
     * @return string
     */
    public static function arrayToXml(array $data): string
    {
        $xml = '<xml>';
        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $xml .= "<" . $key . ">" . $value . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $value . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * @param string $data
     * @return array
     */
    public static function xmlToArray(string $data): array
    {
        try {
            return json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), TRUE);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param string $data
     * @return array
     */
    public static function jsonToArray(string $data): array
    {
        try {
            return \GuzzleHttp\json_decode($data, TRUE);
        } catch (\InvalidArgumentException $e) {
            return [];
        }
    }
}