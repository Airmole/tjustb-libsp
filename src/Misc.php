<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbOpacsys\Exception\Exception;

class Misc extends Base
{
    public function dict(): array
    {
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/groupResource/dict', '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }
}