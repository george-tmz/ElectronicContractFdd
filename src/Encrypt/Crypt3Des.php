<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-2-27
 * Time: 下午3:41
 */

namespace georgeT\ElectronicContractFdd\Encrypt;

/**
 * Class Crypt3Des
 * @package georgeT\ElectronicContractFdd\Server
 */
class Crypt3Des
{
    /**
     * 3DES 加密类型
     */
    const CIPHER = 'des-ede3';

    /**
     * @param string $data 待加密的明文信息数据。
     * @param string $key
     * @return array
     */
    public static function encrypt(string $data, string $key): array
    {
        try {
            if (!in_array(self::CIPHER, openssl_get_cipher_methods())) {
                throw new \Exception('未知加密方法');
            }
            $ivLen  = openssl_cipher_iv_length(self::CIPHER);
            $iv     = openssl_random_pseudo_bytes($ivLen);
            $result = bin2hex(openssl_encrypt($data, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv));
            if (!$result) {
                throw new \Exception('加密失败');
            }
            return [TRUE, $result];
        } catch (\Exception $e) {
            return [FALSE, $e->getMessage()];
        }
    }
}