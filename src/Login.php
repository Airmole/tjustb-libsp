<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class Login extends Base
{
    /**
     * SSO登录
     * @param string $ticket 登录凭证
     * @return array
     * @throws Exception
     */
    public function ssoLogin(string $ticket): array
    {
        $url = self::LOGIN_SERVICE_URL . "/auth3/tjustb/cas/index?ticket={$ticket}";
        $result = $this->httpRequest('GET', $url, '', '', [
            // 'Host: tyrzfw.chaoxing.com',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
        ], true);
        if ($result['code'] != self::CODE_REDIRECT) throw new Exception('登录失败1' . json_encode($result));

        $jsessionIdCookie = $this->getCookieFromHeader('JSESSIONID', $result['data']);
        if (empty($jsessionIdCookie)) throw new Exception('登录失败 $jsessionIdCookie' . json_encode($result));
        $this->insertChaoxingCookie('JSESSIONID', $jsessionIdCookie);
        $routeCookie = $this->getCookieFromHeader('route', $result['data']);
        if (!empty($routeCookie)) $this->insertChaoxingCookie('route', $routeCookie);

        $this->insertChaoxingCookie('casInfo', '');
        $this->insertChaoxingCookie('casEnc', '');
        $this->insertChaoxingCookie('casFrom', 'login');;
        $this->insertChaoxingCookie('firstReferer', $this->libspUrl . '/find/sso/login/tjustb/0?opacRoute=/Home');


        // 访问 https://tyrzfw.chaoxing.com/auth3/tjustb/cas/index
        $nextUrl = $this->getLocationFromRedirectHeader($result['data']);
        $redirect = $this->httpRequest('GET', $nextUrl, '', $this->getChaoxingCookieString(), [
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'Referer: https://authserver.tjustb.cn/'
        ], true);
        if ($redirect['code'] != self::CODE_SUCCESS) throw new Exception('重定向失败 auth3/tjustb/cas/index' . json_encode($redirect));

        $auth3Cookie = $this->getCookieFromHeader('auth3_login', $redirect['data']);
        if (!empty($auth3Cookie)) $this->insertChaoxingCookie('auth3_login', $auth3Cookie);

        preg_match('/casreceive(.*?)";/', $redirect['data'], $nextUrlPara);
        $nextUrlPara = $nextUrlPara[1] ?? '';
        if (empty($nextUrlPara)) throw new Exception('获取casreceive参数失败' . json_encode($redirect));
        $nextUrlPara = $this->unicode2utf8($nextUrlPara);

        $nextUrl = 'https://passport2-api.chaoxing.com/api/v2/casreceive' . $nextUrlPara;
        $firstRefererCookie = 'firstReferer=https://findtjustb.libsp.cn/find/sso/login/tjustb/0?opacRoute=/Home';

        $redirect = $this->httpRequest('GET', $nextUrl, '', $firstRefererCookie, [
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'Referer: https://authserver.tjustb.cn/'
        ], true);
        if ($redirect['code'] != self::CODE_SUCCESS) {
            echo($redirect['data']);die;
        }
        if ($redirect['code'] != self::CODE_SUCCESS) throw new Exception('重定向失败 casreceive' . json_encode($redirect));

        $fidCookie = $this->getCookieFromHeader('fid', $redirect['data']);
        if (!empty($fidCookie)) $this->insertChaoxingCookie('fid', $fidCookie);
        $uidCookie = $this->getCookieFromHeader('_uid', $redirect['data']);
        if (!empty($uidCookie)) $this->insertChaoxingCookie('_uid', $uidCookie);
        $dCookie = $this->getCookieFromHeader('_d', $redirect['data']);
        if (!empty($dCookie)) $this->insertChaoxingCookie('_d', $dCookie);
        $uidUpperCookie = $this->getCookieFromHeader('UID', $redirect['data']);
        if (!empty($uidUpperCookie)) $this->insertChaoxingCookie('UID', $uidUpperCookie);
        $vc3Cookie = $this->getCookieFromHeader('vc3', $redirect['data']);
        if (!empty($vc3Cookie)) $this->insertChaoxingCookie('vc3', $vc3Cookie);
        $ufCookie = $this->getCookieFromHeader('uf', $redirect['data']);
        if (!empty($ufCookie)) $this->insertChaoxingCookie('uf', $ufCookie);
        $cxpCookie = $this->getCookieFromHeader('cx_p_token', $redirect['data']);
        if (!empty($cxpCookie)) $this->insertChaoxingCookie('cx_p_token', $cxpCookie);
        $pauthCookie = $this->getCookieFromHeader('p_auth_token', $redirect['data']);
        if (!empty($pauthCookie)) $this->insertChaoxingCookie('p_auth_token', $pauthCookie);
        $xxtencCookie = $this->getCookieFromHeader('xxtenc', $redirect['data']);
        if (!empty($xxtencCookie)) $this->insertChaoxingCookie('xxtenc', $xxtencCookie);
        $dsstashCookie = $this->getCookieFromHeader('DSSTASH_LOG', $redirect['data']);
        if (!empty($dsstashCookie)) $this->insertChaoxingCookie('DSSTASH_LOG', $dsstashCookie);
        $routeCookie = $this->getCookieFromHeader('route', $redirect['data']);
        if (!empty($routeCookie)) $this->insertChaoxingCookie('route', $routeCookie);

        preg_match('/&refer=(.*?)&/', $nextUrl, $referUrl);
        $referUrl = $referUrl[1] ?? '';
        if (empty($referUrl)) throw new Exception('匹配refer URL 失败' . $nextUrl);

        $nextUrl = urldecode($referUrl);
        $redirect = $this->httpRequest('GET', $nextUrl, '', $this->cookie, [
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'Referer: https://passport2-api.chaoxing.com/'
        ], true);
        if ($redirect['code'] != self::CODE_REDIRECT) throw new Exception('重定向refer失败' . json_encode($redirect));

        $sessionCookie = $this->getCookieFromHeader('SESSION', $redirect['data']);
        if (!empty($sessionCookie)) $this->insertCookie('SESSION', $sessionCookie);
        $passportCookie = $this->getCookieFromHeader('_passport_login', $redirect['data']);
        if (!empty($passportCookie)) $this->insertCookie('_passport_login', $passportCookie);

        preg_match('/Home\?jwt=(.*?)&jwtHeader=/', $redirect['data'], $jwtCookie);
        $jwtCookie = empty($jwtCookie[1]) ? '' : $jwtCookie[1];
        preg_match('/&jwtHeader=(.*?)/', $redirect['data'], $jwtHeaderCookie);
        $jwtHeaderCookie = empty($jwtHeaderCookie[1]) ? 'jwtOpacAuth' : $jwtHeaderCookie[1];
        if (empty($jwtCookie) || empty($jwtHeaderCookie)) throw new Exception('获取jwt失败' . json_encode($redirect));

        $this->insertCookie('SameSite', '');
        $this->insertCookie('jwt', $jwtCookie);
        $this->insertCookie('jwtHeader', $jwtHeaderCookie);

        return [
            'code' => self::CODE_SUCCESS,
            'cookie' => $this->getCookieString(),
            'data' => $redirect['data']
        ];
    }

    /**
     * 获取用户信息
     * @param array $cookie
     * @return array
     * @throws Exception
     */
    public function userInfo(array $cookie = []): array
    {
        if (empty($cookie)) $cookie = $this->cookieArray;

        $result = $this->httpRequest('GET', '/oga/userinfo', '', $this->getCookieString($cookie), [
            'Referer: https://findtjustb.libsp.cn/'
        ]);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . json_encode($result));
        return json_decode($result['data'], true);
    }
}