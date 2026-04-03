<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbOpacsys\Exception\Exception;

class Popular extends Base
{

    public function popularSearchSimple(): array
    {
        $result = $this->httpRequest('GET', '/find/popularSearch/get?searchValue=0');

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function getHotBorrow(
        int $page = 1,
        int $rows = 10,
        string $disCode = null,
        int $statRange = 30,
        int $indexFlag = 1,
        string $libCode = '',
        int $sortType = 1,
        string $classNo = ''
    ): array
    {
        $body = [
            'page' => $page,
            'rows' => $rows,
            'disCode' => $disCode,
            'statRange' => $statRange,
            'indexFlag' => $indexFlag,
            'libCode' => $libCode,
            'sortType' => $sortType,
            'classNo' => $classNo
        ];
        $result = $this->httpRequest('POST', '/find/index/getHotLoan', $body);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function getNewBook(
        int $page = 1,
        int $rows = 10,
        string $time = "2",
        string $docCode = "1",
    )
    {
        $body = [
            'page' => $page,
            'rows' => $rows,
            'time' => $time,
            'docCode' => $docCode,
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/index/getNewBook', $body,'', $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

}