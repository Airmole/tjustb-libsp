<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class Misc extends Base
{
    /**
     * 获取数据字典
     *
     * @see https://gist.github.com/Airmole/147443e2f1ed0222769f033794e6fb09
     * @return array
     * @throws Exception
     */
    public function dict(): array
    {
        $headers = ["Referer: {$this->libspUrl}/"];
        return $this->requestJson('POST', '/find/groupResource/dict', '', $this->cookie, $headers);
    }
}
