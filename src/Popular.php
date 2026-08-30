<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class Popular extends Base
{
    /**
     * 获取热门搜索
     *
     * @return array
     * @throws Exception
     */
    public function popularSearchSimple(): array
    {
        return $this->requestJson('GET', '/find/popularSearch/get?searchValue=0', '', $this->cookie);
    }

    /**
     * 获取学科分类（disCode）列表
     *
     * @return array
     * @throws Exception
     */
    public function disCodeList(): array
    {
        return $this->requestJson('GET', '/find/index/getDiscipline?disCode=', '', $this->cookie);
    }

    /**
     * 获取热门借阅
     *
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param string|null $disCode 学科分类
     * @param int $statRange 统计范围天数
     * @param int $indexFlag 是否首页请求
     * @param string $libCode 图书馆代码
     * @param int $sortType 排序方式
     * @param string $classNo 分类号
     * @return array
     * @throws Exception
     */
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
            'libCode'   => $libCode,
            'disCode'   => $disCode,
            'statRange' => $statRange,
            'page'      => $page,
            'rows'      => $rows,
            'sortType'  => $sortType,
        ];
        if ($indexFlag == 1) $body['indexFlag'] = $indexFlag;
        if (!empty($classNo)) $body['classNo'] = $classNo;

        $headers = ["Referer: {$this->libspUrl}/"];
        return $this->requestJson('POST', '/find/index/getHotLoan', $body, $this->cookie, $headers);
    }

    /**
     * 获取最新图书
     *
     * @return array
     * @throws Exception
     */
    public function getNewBook(
        int $page = 1,
        int $rows = 10,
        string $disCode = '',
        string $callNo = '',
        string $sortField = 'in_date',
        string $sortClause = 'desc',
        string $time = '2',
        string $searchWord = '',
        string $docCode = '1',
        array $campusId = [],
        string $libCode = '',
        string $locationId = '',
        string $opacSearchLangCode = ''
    ): array
    {
        $body = [
            'page'               => $page,
            'rows'               => $rows,
            'disCode'            => $disCode,
            'callNo'             => $callNo,
            'sortField'          => $sortField,
            'sortClause'         => $sortClause,
            'time'               => $time,
            'docCode'            => $docCode,
            'campusId'           => $campusId,
            'libCode'            => $libCode,
            'locationId'         => $locationId,
            'opacSearchLangCode' => $opacSearchLangCode,
            'searchWord'         => $searchWord,
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        return $this->requestJson('POST', '/find/index/getNewBook', $body, $this->cookie, $headers);
    }
}
