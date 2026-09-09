<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 19-3-1
 * Time: 上午11:57
 */

namespace georgeT\ElectronicContractFdd\Http;

use Exception;

/**
 * Class CurlClient
 * @package georgeT\ElectronicContractFdd\Http
 */
class CurlClient
{
    /**
     * @var array
     */
    private $tmpFiles = [];

    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     * @return Response
     * @throws Exception
     */
    public function request($method, $uri, array $options = [])
    {
        $this->tmpFiles = [];
        $url = $this->buildUrl($uri, isset($options['query']) ? $options['query'] : null);
        $headers = $this->normalizeHeaders(isset($options['headers']) ? $options['headers'] : []);
        $method = strtoupper($method);

        $ch = curl_init();
        if ($ch === false) {
            throw new Exception('Failed to initialize cURL');
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $this->applyMethod($ch, $method);

        if (isset($options['timeout'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT, (int)$options['timeout']);
        }
        if (isset($options['connect_timeout'])) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int)$options['connect_timeout']);
        }
        if (!empty($options['debug'])) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }
        if (isset($options['proxy'])) {
            curl_setopt($ch, CURLOPT_PROXY, $options['proxy']);
        }
        if (isset($options['cert'])) {
            $this->applyCert($ch, $options['cert']);
        }

        $body = $this->buildBody($options, $headers);
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if ($headers !== []) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $responseBody = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $statusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->cleanupTmpFiles();

        if ($responseBody === false) {
            throw new Exception($error !== '' ? $error : 'cURL request failed', $errno);
        }

        return new Response($statusCode, $responseBody);
    }

    /**
     * @param resource $ch
     * @param string   $method
     * @return void
     */
    private function applyMethod($ch, $method)
    {
        if ($method === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            return;
        }
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            return;
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    }

    /**
     * @param string     $uri
     * @param mixed|null $query
     * @return string
     */
    private function buildUrl($uri, $query)
    {
        if ($query === null || $query === '' || $query === []) {
            return $uri;
        }
        if (is_array($query)) {
            $query = http_build_query($query);
        }
        $separator = strpos($uri, '?') === false ? '?' : '&';

        return $uri . $separator . $query;
    }

    /**
     * @param array $headers
     * @return array
     */
    private function normalizeHeaders(array $headers)
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            if (is_int($name)) {
                $normalized[] = $value;
                continue;
            }
            $normalized[] = $name . ': ' . (is_array($value) ? implode(', ', $value) : $value);
        }

        return $normalized;
    }

    /**
     * @param resource   $ch
     * @param string|array $cert
     * @return void
     */
    private function applyCert($ch, $cert)
    {
        if (is_array($cert)) {
            curl_setopt($ch, CURLOPT_SSLCERT, $cert[0]);
            if (isset($cert[1])) {
                curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $cert[1]);
            }
            return;
        }
        curl_setopt($ch, CURLOPT_SSLCERT, $cert);
    }

    /**
     * @param array $options
     * @param array $headers
     * @return mixed|null
     */
    private function buildBody(array $options, array &$headers)
    {
        if (isset($options['multipart'])) {
            return $this->buildMultipart($options['multipart']);
        }
        if (isset($options['form_params'])) {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            return http_build_query($options['form_params']);
        }
        if (isset($options['body'])) {
            return $options['body'];
        }

        return null;
    }

    /**
     * @param array $multipart
     * @return array
     */
    private function buildMultipart(array $multipart)
    {
        $fields = [];
        foreach ($multipart as $part) {
            $name = $part['name'];
            $contents = isset($part['contents']) ? $part['contents'] : '';
            $filename = isset($part['filename']) ? $part['filename'] : null;
            $mime = isset($part['headers']['Content-Type']) ? $part['headers']['Content-Type'] : '';

            if ($contents instanceof \CURLFile) {
                $fields[$name] = $contents;
                continue;
            }

            if (is_resource($contents)) {
                $fields[$name] = $this->resourceToCurlFile($contents, $filename, $mime);
                continue;
            }

            if ($filename !== null) {
                $tmp = $this->createTmpFile((string)$contents);
                $fields[$name] = new \CURLFile($tmp, $mime !== '' ? $mime : null, $filename);
                continue;
            }

            $fields[$name] = $contents;
        }

        return $fields;
    }

    /**
     * @param resource    $contents
     * @param string|null $filename
     * @param string      $mime
     * @return \CURLFile
     */
    private function resourceToCurlFile($contents, $filename, $mime)
    {
        $meta = stream_get_meta_data($contents);
        $path = isset($meta['uri']) ? $meta['uri'] : '';
        if ($path !== '' && strpos($path, 'php://') !== 0 && @is_file($path)) {
            return new \CURLFile(
                $path,
                $mime !== '' ? $mime : null,
                $filename !== null ? $filename : basename($path)
            );
        }

        $data = stream_get_contents($contents);
        if ($data === false) {
            $data = '';
        }
        $tmp = $this->createTmpFile($data);

        return new \CURLFile($tmp, $mime !== '' ? $mime : null, $filename !== null ? $filename : 'file');
    }

    /**
     * @param string $contents
     * @return string
     */
    private function createTmpFile($contents)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'fdd_');
        file_put_contents($tmp, $contents);
        $this->tmpFiles[] = $tmp;

        return $tmp;
    }

    /**
     * @return void
     */
    private function cleanupTmpFiles()
    {
        foreach ($this->tmpFiles as $tmp) {
            if (is_file($tmp)) {
                @unlink($tmp);
            }
        }
        $this->tmpFiles = [];
    }
}
