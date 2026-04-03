<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbOpacsys\Exception\Exception;

class Base
{
    /**
     * @var string 默认libsp系统URL
     */
    public const DEFAULT_LIBSP_URL = 'https://findtjustb.libsp.cn';
    /**
     * @var string libsp系统 URL域名
     */
    public string $libspUrl;
    /**
     * @var string 代理地址
     */
    public string $proxy;
    /**
     * @var string 配置文件路径
     */
    public string $configPath;

    /**
     * @var string 用户账号
     */
    public string $userCode;

    /**
     * @var string 用户已登录cookie
     */
    public string $cookie;

    /**
     * @var int 默认请求成功响应代码
     */
    public const CODE_SUCCESS = 200;

    /**
     * @var int 默认请求重定向响应代码
     */
    public const CODE_REDIRECT = 302;

    public function __construct()
    {
        // 设置默认配置文件
        if (empty($this->configPath)) $this->setConfigPath();
        // 未配置libsp URL 自动配置
        if (empty($this->libspUrl)) $this->setLibspUrl();
        // 设置代理
        $this->proxy = $this->getConfig('LIBSP_PROXY', '');

        // 获取cookie
        $this->getCookie();
    }

    /**
     * 设置配置文件路径
     * @param string $path
     * @return void
     */
    public function setConfigPath(string $path = ''): void
    {
        $defaultPath = $_SERVER['DOCUMENT_ROOT'] . '/../.env';
        if ($path === '') $path = $defaultPath;
        $this->configPath = $path;
    }

    /**
     * 设置URL
     * @param string $url
     * @return void
     */
    public function setLibspUrl(string $url = self::DEFAULT_LIBSP_URL): void
    {
        if (empty($url)) $url = self::DEFAULT_LIBSP_URL;
        $configLibspUrl = $this->getConfig('LIBSP_URL', '');
        if (!empty($configLibspUrl)) $url = $configLibspUrl;
        $this->libspUrl = $url;
    }

    /**
     * 获取配置项
     * @param string $key
     * @param $default
     * @param string $path
     * @return string
     */
    public function getConfig(string $key, $default = null, string $path = ''): string
    {
        $configs = $this->configPath;
        if (!file_exists($configs) && $path === '') return $default;
        preg_match("/{$key}=(.*?)\n/", file_get_contents($configs), $matchedConfig);
        if (empty($matchedConfig) && $path === '') return $default;
        return $matchedConfig[1] ?: $default;
    }

    /**
     * HTTP请求
     * @param string $method 请求方式
     * @param string $url 请求URL
     * @param mixed $body 请求体
     * @param mixed $cookie Cookie
     * @param array $headers 请求头
     * @param bool $showHeaders 是否返回请求头
     * @param bool $followLocation 是否跟随跳转
     * @param int $timeout 超时时间
     * @return array 响应结果
     */
    public function httpRequest(
        string $method = 'GET',
        string $url = '',
        mixed  $body = '',
        mixed  $cookie = '',
        array  $headers = [],
        bool   $showHeaders = false,
        bool   $followLocation = false,
        int    $timeout = 10
    ): array
    {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = $this->libspUrl . (str_starts_with($url, '/') ? $url : "/{$url}");
        }
        $url = trim($url);

        $defaultHeaders = [
            'sec-ch-ua: "Chromium";v="146", "Not-A.Brand";v="24", "Microsoft Edge";v="146"',
            'sec-ch-ua-mobile: ?0',
            'Accept: application/json, text/plain, */*',
            'groupCode: 200960',
            'x-lang: CHI',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
            'Accept-Encoding: gzip, deflate, br, zstd',
            'Accept-Language: zh-CN,zh;q=0.9',
            'content-language: zh_CN',
            "Origin: " . $this->libspUrl,
            'Sec-Fetch-Site: same-origin',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'Content-Type: application/json;charset=UTF-8'
        ];
        $headers = array_merge($defaultHeaders, $headers);

        if (is_string($cookie) && !empty($cookie)) {
            $cookie = trim($cookie);
            $headers[] = !str_starts_with($cookie, 'Cookie:') ? "Cookie: {$cookie}" : $cookie;
        }

        $timeout = (int)$this->getConfig('OPACSYS_TIMEOUT', $timeout);

        $requestOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'gzip, deflate, br, zstd',
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => $followLocation,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => $showHeaders,
        ];

        if (!empty($body)) {
            $requestOptions[CURLOPT_POSTFIELDS] = is_array($body) ? json_encode($body) : $body;
        }

        if (!empty($this->proxy)) $requestOptions[CURLOPT_PROXY] = $this->proxy;

        $ch = curl_init();
        curl_setopt_array($ch, $requestOptions);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['code' => 0, 'data' => 'cURL Error: ' . $error];
        }

        curl_close($ch);

        return ['code' => (int)$httpCode, 'data' => $response];
    }

    /**
     * 从响应头中获取Cookie
     * @param string $key Cookie名称
     * @param string $headerString 响应头字符串
     * @return string Cookie值
     */
    public function getCookieFromHeader(string $key, string $headerString = ''): string
    {
        preg_match("/Set-Cookie: {$key}=(.*?);/", $headerString, $cookieValue);
        return $cookieValue[1] ?? '';
    }

    /**
     * 从响应头中获取跳转地址
     * @param string $header 响应头字符串
     * @return string 跳转地址
     */
    public function getLocationFromRedirectHeader(string $header = ''): string
    {
        preg_match('/Location: (.*)/', $header, $nextUrl);
        $nextUrl = $nextUrl[1] ?? '';
        return trim($nextUrl);
    }

    /**
     * 获取cookie
     * @return string
     */
    public function getCookie(): string
    {
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', '/find/findConfig/getMenuList','', '', $headers, true);
        $cookie = $this->getCookieFromHeader('route', $result['data']);
        $cookie = "route={$cookie}";
        $this->cookie = $cookie;
        return $cookie;
    }
}