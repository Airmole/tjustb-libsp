<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class Base
{
    /** @var string 默认libsp系统URL */
    public const DEFAULT_LIBSP_URL = 'https://findtjustb.libsp.cn';

    /** @var string libsp系统超星统一登录认证服务URL */
    public const LOGIN_SERVICE_URL = 'https://tyrzfw.chaoxing.com';

    /** @var int 默认请求成功响应代码 */
    public const CODE_SUCCESS = 200;

    /** @var int 默认请求重定向响应代码 */
    public const CODE_REDIRECT = 302;

    /** @var string libsp系统URL域名 */
    public string $libspUrl = '';

    /** @var string 代理地址 */
    public string $proxy = '';

    /** @var string 配置文件路径 */
    public string $configPath = '';

    /** @var string 用户账号 */
    public string $userCode = '';

    /** @var string 用户已登录cookie */
    public string $cookie = '';

    /** @var array 用户cookie数组 */
    public array $cookieArray = [];

    /** @var array 超星cookie数组 */
    public array $chaoxingCookieArray = [];

    public function __construct()
    {
        $this->initConfig();
        $this->initLibspUrl();
        $this->proxy = $this->getConfig('LIBSP_PROXY', '');
        $this->getNewCookie();
    }

    /**
     * 初始化配置路径
     */
    protected function initConfig(): void
    {
        if (empty($this->configPath)) {
            $this->configPath = ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/../.env';
        }
    }

    /**
     * 初始化 libsp URL
     */
    protected function initLibspUrl(): void
    {
        if (empty($this->libspUrl)) {
            $this->libspUrl = $this->getConfig('LIBSP_URL', self::DEFAULT_LIBSP_URL) ?: self::DEFAULT_LIBSP_URL;
        }
    }

    /**
     * 设置配置文件路径
     */
    public function setConfigPath(string $path = ''): void
    {
        $this->configPath = $path ?: (($_SERVER['DOCUMENT_ROOT'] ?? '') . '/../.env');
    }

    /**
     * 设置URL
     */
    public function setLibspUrl(string $url = self::DEFAULT_LIBSP_URL): void
    {
        $configUrl = $this->getConfig('LIBSP_URL', '');
        $this->libspUrl = !empty($configUrl) ? $configUrl : $url;
    }

    /**
     * 获取配置项
     */
    public function getConfig(string $key, mixed $default = null, string $path = ''): mixed
    {
        $configPath = $path ?: $this->configPath;
        if (!file_exists($configPath)) return $default;

        $content = file_get_contents($configPath);
        if ($content === false) return $default;

        if (preg_match("/^{$key}=(.*)$/m", $content, $matched)) {
            $value = trim($matched[1]);
            return $value !== '' ? $value : $default;
        }
        return $default;
    }

    /**
     * 发送 HTTP 请求
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
            'Origin: ' . $this->libspUrl,
            'Sec-Fetch-Site: same-origin',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'Content-Type: application/json;charset=UTF-8',
            'Referer: ' . self::DEFAULT_LIBSP_URL . '/'
        ];
        $headers = array_merge($defaultHeaders, $headers);

        if (is_string($cookie) && !empty($cookie)) {
            $cookie = trim($cookie);
            $headers[] = str_starts_with($cookie, 'Cookie:') ? $cookie : "Cookie: {$cookie}";

            $cookieArr = $this->parseCookieString($cookie);
            if (isset($cookieArr['jwt']) && str_contains($url, $this->libspUrl)) {
                $headers[] = 'jwtOpacAuth: ' . $cookieArr['jwt'];
            }
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

        if (!empty($this->proxy)) {
            $requestOptions[CURLOPT_PROXY] = $this->proxy;
        }

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
     * 发送 JSON 请求并自动解析响应
     *
     * @throws Exception
     */
    public function requestJson(
        string $method = 'GET',
        string $url = '',
        mixed  $body = '',
        mixed  $cookie = '',
        array  $headers = [],
        bool   $showHeaders = false,
        bool   $followLocation = false,
        int    $timeout = 10,
        string $errorMessage = '请求失败'
    ): array
    {
        $result = $this->httpRequest($method, $url, $body, $cookie, $headers, $showHeaders, $followLocation, $timeout);

        if ($result['code'] !== self::CODE_SUCCESS) {
            throw new Exception("{$errorMessage}：HTTP {$result['code']}");
        }

        $data = json_decode($result['data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("{$errorMessage}：JSON 解析失败");
        }

        return $data;
    }

    /**
     * 从响应头中获取 Cookie
     */
    public function getCookieFromHeader(string $key, string $headerString = ''): string
    {
        if (preg_match("/Set-Cookie: {$key}=(.*?);/", $headerString, $cookieValue)) {
            return $cookieValue[1];
        }
        return '';
    }

    /**
     * 从响应头中获取跳转地址
     */
    public function getLocationFromRedirectHeader(string $header = ''): string
    {
        if (preg_match('/Location:\s*(.*)/', $header, $nextUrl)) {
            return trim($nextUrl[1]);
        }
        return '';
    }

    /**
     * 获取 cookie
     */
    public function getNewCookie(): string
    {
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', '/find/findConfig/getMenuList', '', '', $headers, true);
        $cookie = $this->getCookieFromHeader('route', $result['data']);
        $this->insertCookie('route', $cookie);
        return $this->getCookieString();
    }

    /**
     * 插入 cookie
     */
    public function insertCookie(string $key, string $value): void
    {
        $this->cookieArray[$key] = $value;
        $this->cookie = $this->getCookieString($this->cookieArray);
    }

    /**
     * 获取 Cookie 字符串
     */
    public function getCookieString(array $cookie = []): string
    {
        if (empty($cookie)) {
            $cookie = $this->cookieArray;
        }
        return implode('; ', array_map(
            fn($k, $v) => "{$k}={$v}",
            array_keys($cookie),
            array_values($cookie)
        ));
    }

    /**
     * 解析 Cookie 字符串
     */
    public function parseCookieString(string $cookieString = ''): array
    {
        if (str_starts_with($cookieString, 'Cookie: ')) {
            $cookieString = substr($cookieString, 8);
        }

        $cookieArray = [];
        foreach (explode(';', $cookieString) as $pair) {
            $pair = trim($pair);
            $pos = strpos($pair, '=');
            if ($pos === false) continue;
            $cookieArray[trim(substr($pair, 0, $pos))] = trim(substr($pair, $pos + 1));
        }
        $this->cookieArray = $cookieArray;
        $this->cookie = $this->getCookieString($cookieArray);
        return $cookieArray;
    }

    /**
     * 解析 Cookie 数组
     */
    public function parseCookieArray(array $cookie = []): string
    {
        $this->cookieArray = $cookie;
        $this->cookie = $this->getCookieString($cookie);
        return $this->cookie;
    }

    /**
     * 插入超星 cookie
     */
    public function insertChaoxingCookie(string $key, string $value): void
    {
        $this->chaoxingCookieArray[$key] = $value;
    }

    /**
     * 获取超星 Cookie 字符串
     */
    public function getChaoxingCookieString(array $chaoxingCookieArray = []): string
    {
        if (empty($chaoxingCookieArray)) {
            $chaoxingCookieArray = $this->chaoxingCookieArray;
        }
        return implode('; ', array_map(
            fn($k, $v) => "{$k}={$v}",
            array_keys($chaoxingCookieArray),
            array_values($chaoxingCookieArray)
        ));
    }

    /**
     * 将 unicode 字符串转为 UTF-8
     */
    public function unicode2utf8(string $str): string
    {
        if (!$str) return '';
        $decode = json_decode($str);
        if ($decode) return $decode;
        $str = '["' . $str . '"]';
        $decode = json_decode($str);
        if (count($decode) == 1) {
            return $decode[0];
        }
        return $str;
    }
}
