<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class Login extends Base
{
    /**
     * SSO 登录
     *
     * @param string $ticket 登录凭证
     * @return array
     * @throws Exception
     */
    public function ssoLogin(string $ticket): array
    {
        $url = self::LOGIN_SERVICE_URL . "/auth3/tjustb/cas/index?ticket={$ticket}";
        $result = $this->httpRequest('GET', $url, '', '', [
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
        ], true);

        if ($result['code'] !== self::CODE_REDIRECT) {
            throw new Exception('登录失败：预期重定向但收到 HTTP ' . $result['code']);
        }

        $jsessionIdCookie = $this->getCookieFromHeader('JSESSIONID', $result['data']);
        if (empty($jsessionIdCookie)) {
            throw new Exception('登录失败：未能获取 JSESSIONID Cookie');
        }
        $this->insertChaoxingCookie('JSESSIONID', $jsessionIdCookie);

        $routeCookie = $this->getCookieFromHeader('route', $result['data']);
        if (!empty($routeCookie)) {
            $this->insertChaoxingCookie('route', $routeCookie);
        }

        $this->insertChaoxingCookie('casInfo', '');
        $this->insertChaoxingCookie('casEnc', '');
        $this->insertChaoxingCookie('casFrom', 'login');
        $this->insertChaoxingCookie('firstReferer', $this->libspUrl . '/find/sso/login/tjustb/0?opacRoute=/Home');

        // 访问认证服务，获取授权页面
        $nextUrl = $this->getLocationFromRedirectHeader($result['data']);
        $redirect = $this->httpRequest('GET', $nextUrl, '', $this->getChaoxingCookieString(), [
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'Referer: https://authserver.tjustb.cn/',
        ], true);

        if ($redirect['code'] !== self::CODE_SUCCESS) {
            throw new Exception('重定向失败：auth3/tjustb/cas/index ' . json_encode($redirect));
        }

        $auth3Cookie = $this->getCookieFromHeader('auth3_login', $redirect['data']);
        if (!empty($auth3Cookie)) {
            $this->insertChaoxingCookie('auth3_login', $auth3Cookie);
        }

        if (!preg_match('/casreceive(.*?)";/', $redirect['data'], $nextUrlPara)) {
            throw new Exception('获取 casreceive 参数失败');
        }
        $nextUrlPara = $this->unicode2utf8($nextUrlPara[1]);

        $nextUrl = 'https://passport2-api.chaoxing.com/api/v2/casreceive' . $nextUrlPara;
        $firstRefererCookie = 'firstReferer=https://findtjustb.libsp.cn/find/sso/login/tjustb/0?opacRoute=/Home';

        $redirect = $this->httpRequest('GET', $nextUrl, '', $firstRefererCookie, [
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'Referer: https://authserver.tjustb.cn/',
        ], true);

        if ($redirect['code'] !== self::CODE_SUCCESS) {
            throw new Exception('重定向失败：casreceive ' . json_encode($redirect));
        }

        // 提取超星认证 Cookie
        foreach ([
            'fid', '_uid', '_d', 'UID', 'vc3', 'uf',
            'cx_p_token', 'p_auth_token', 'xxtenc', 'DSSTASH_LOG', 'route',
        ] as $cookieKey) {
            $cookieValue = $this->getCookieFromHeader($cookieKey, $redirect['data']);
            if (!empty($cookieValue)) {
                $this->insertChaoxingCookie($cookieKey, $cookieValue);
            }
        }

        if (!preg_match('/&refer=(.*?)&/', $nextUrl, $referUrl)) {
            throw new Exception('匹配 refer URL 失败：' . $nextUrl);
        }

        // 访问 refer URL 完成最终登录
        $nextUrl = urldecode($referUrl[1]);
        $redirect = $this->httpRequest('GET', $nextUrl, '', $this->cookie, [
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'Referer: https://passport2-api.chaoxing.com/',
        ], true);

        if ($redirect['code'] !== self::CODE_REDIRECT) {
            throw new Exception('重定向 refer 失败：' . json_encode($redirect));
        }

        $sessionCookie = $this->getCookieFromHeader('SESSION', $redirect['data']);
        if (!empty($sessionCookie)) {
            $this->insertCookie('SESSION', $sessionCookie);
        }
        $passportCookie = $this->getCookieFromHeader('_passport_login', $redirect['data']);
        if (!empty($passportCookie)) {
            $this->insertCookie('_passport_login', $passportCookie);
        }

        if (!preg_match('/Home\?jwt=(.*?)&jwtHeader=/', $redirect['data'], $jwtMatches)) {
            throw new Exception('获取 jwt 失败：' . json_encode($redirect));
        }
        $jwtCookie = $jwtMatches[1];
        preg_match('/&jwtHeader=(.*?)/', $redirect['data'], $jwtHeaderMatches);
        $jwtHeaderCookie = $jwtHeaderMatches[1] ?? 'jwtOpacAuth';

        $this->insertCookie('SameSite', '');
        $this->insertCookie('jwt', $jwtCookie);
        $this->insertCookie('jwtHeader', $jwtHeaderCookie);

        return [
            'code'   => self::CODE_SUCCESS,
            'cookie' => $this->getCookieString(),
            'data'   => $redirect['data'],
        ];
    }

    /**
     * 获取用户信息
     *
     * @param array $cookie Cookie 数组
     * @return array
     * @throws Exception
     */
    public function userInfo(array $cookie = []): array
    {
        if (empty($cookie)) {
            $cookie = $this->cookieArray;
        }
        $headers = ['Referer: https://findtjustb.libsp.cn/'];
        return $this->requestJson('GET', '/oga/userinfo', '', $this->getCookieString($cookie), $headers);
    }
}
