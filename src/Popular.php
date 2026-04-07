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

    public function disCodeList(): array
    {
        $result = $this->httpRequest('GET', '/find/index/getDiscipline?disCode=');
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function getHotBorrow(
        int $page = 1,
        int $rows = 10,
        string $disCode = null,
        int $statRange = 30,
        int $indexFlag = 0,
        string $libCode = '',
        int $sortType = 1,
        string $classNo = ''
    ): array
    {
        $body = [
            'libCode' => $libCode,
            'disCode' => $disCode,
            'statRange' => $statRange,
            'page' => $page,
            'rows' => $rows,
            'sortType' => $sortType,
        ];
        if ($indexFlag == 1) $body['indexFlag'] = $indexFlag;
        if (!empty($classNo)) $body['classNo'] = $classNo;
        $headers = ["Referer: {$this->libspUrl}/"];

        $result = $this->httpRequest('POST', '/find/index/getHotLoan', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function getNewBook(
        int $page = 1,
        int $rows = 10,
        string $disCode = '',
        string $callNo = '',
        string $sortField = 'in_date',
        string $sortClause = 'desc',
        string $time = "2",
        string $searchWord = '',
        string $docCode = "1",
        array $campusId = [],
        string $libCode = '',
        string $locationId = '',
        string $opacSearchLangCode = ''
    )
    {
        $body = [
            'page' => $page,
            'rows' => $rows,
            'disCode' => $disCode,
            'callNo' => $callNo,
            'sortField' => $sortField,
            'sortClause' => $sortClause,
            'time' => $time,
            'docCode' => $docCode,
            'campusId' => $campusId,
            'libCode' => $libCode,
            'locationId' => $locationId,
            'opacSearchLangCode' => $opacSearchLangCode,
            'searchWord' => $searchWord
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/index/getNewBook', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

}