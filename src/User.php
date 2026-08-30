<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class User extends Base
{
    /**
     * 获取用户限制数据
     *
     * @param string $userId 用户ID
     * @return array
     * @throws Exception
     */
    public function userLimit(string $userId = ''): array
    {
        $query = http_build_query(['userId' => $userId]);
        $url = "/find/user/userLimit?{$query}";
        return $this->requestJson('GET', $url, '', $this->cookie);
    }

    /**
     * 获取用户信息
     *
     * @return array
     * @throws Exception
     */
    public function getUserInfo(): array
    {
        return $this->requestJson('GET', '/find/userInfo/getUserInfo', '', $this->cookie);
    }

    /**
     * 获取每日推荐图书
     *
     * @return array
     * @throws Exception
     */
    public function dailyRecommend(): array
    {
        return $this->requestJson('GET', '/find/subscribe/dailyRecommend', '', $this->cookie);
    }

    /**
     * 获取借阅统计
     *
     * @return array
     * @throws Exception
     */
    public function loanChart(): array
    {
        return $this->requestJson('POST', '/find/loanInfo/loanChart', '{}', $this->cookie);
    }

    /**
     * 获取借阅规则
     *
     * @return array
     * @throws Exception
     */
    public function userLoanRules(): array
    {
        return $this->requestJson('POST', '/find/userInfo/UserLoanRules', '{}', $this->cookie);
    }

    /**
     * 获取当前借阅图书
     *
     * @param int $searchType 搜索类型：1-题名；2-责任者；3-条码号
     * @param string $searchContent 搜索内容
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param int $sortType 排序类型：0-默认；1-借阅时间；2-还书时间；3-逾期天数
     * @param string|null $startDate 开始日期
     * @param string|null $endDate 结束日期
     * @return array
     * @throws Exception
     */
    public function loanList(
        int $searchType = 1,
        string $searchContent = '',
        int $page = 1,
        int $rows = 10,
        int $sortType = 0,
        string $startDate = null,
        string $endDate = null
    ): array
    {
        $body = [
            'searchType'    => $searchType,
            'searchContent' => $searchContent,
            'page'          => $page,
            'rows'          => $rows,
            'sortType'      => $sortType,
            'startDate'     => $startDate,
            'endDate'       => $endDate,
        ];
        return $this->requestJson('POST', '/find/loanInfo/loanList', $body, $this->cookie);
    }

    /**
     * 获取当前借阅现刊
     *
     * @param int $searchType 搜索类型：1-题名；4-ISSN
     * @param string $searchContent 搜索内容
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param int $sortType 排序类型：0-默认
     * @param string|null $startDate 开始日期
     * @param string|null $endDate 结束日期
     * @return array
     * @throws Exception
     */
    public function issueLoanInfoList(
        int $searchType = 1,
        string $searchContent = '',
        int $page = 1,
        int $rows = 10,
        int $sortType = 0,
        string $startDate = null,
        string $endDate = null
    ): array
    {
        $body = [
            'searchType'    => $searchType,
            'searchContent' => $searchContent,
            'page'          => $page,
            'rows'          => $rows,
            'sortType'      => $sortType,
            'startDate'     => $startDate,
            'endDate'       => $endDate,
        ];
        return $this->requestJson('POST', '/find/loanInfo/getIssueLoanInfoList', $body, $this->cookie);
    }

    /**
     * 获取借阅历史
     *
     * @param int $searchType 搜索类型：1-题名；2-责任者；3-条码号
     * @param string $searchContent 搜索内容
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param int $sortType 排序类型：0-默认
     * @param string|null $startDate 开始日期
     * @param string|null $endDate 结束日期
     * @return array
     * @throws Exception
     */
    public function loanHistory(
        int $searchType = 1,
        string $searchContent = '',
        int $page = 1,
        int $rows = 10,
        int $sortType = 0,
        string $startDate = null,
        string $endDate = null
    ): array
    {
        $body = [
            'searchType'    => $searchType,
            'searchContent' => $searchContent,
            'page'          => $page,
            'rows'          => $rows,
            'sortType'      => $sortType,
            'startDate'     => $startDate,
            'endDate'       => $endDate,
        ];
        return $this->requestJson('POST', '/find/loanInfo/loanHistoryList', $body, $this->cookie);
    }

    /**
     * 获取现刊借阅历史
     *
     * @param int $searchType 搜索类型：1-题名；4-ISSN
     * @param string $searchContent 搜索内容
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param int $sortType 排序类型：0-默认
     * @param string|null $startDate 开始日期
     * @param string|null $endDate 结束日期
     * @return array
     * @throws Exception
     */
    public function issueLoanHistory(
        int $searchType = 1,
        string $searchContent = '',
        int $page = 1,
        int $rows = 10,
        int $sortType = 0,
        string $startDate = null,
        string $endDate = null
    ): array
    {
        $body = [
            'searchType'    => $searchType,
            'searchContent' => $searchContent,
            'page'          => $page,
            'rows'          => $rows,
            'sortType'      => $sortType,
            'startDate'     => $startDate,
            'endDate'       => $endDate,
        ];
        return $this->requestJson('POST', '/find/loanInfo/getIssueLoanHistory', $body, $this->cookie);
    }

    /**
     * 续借图书
     *
     * @param array $loanIds 借阅ID数组
     * @return array
     * @throws Exception
     */
    public function renewBooks(array $loanIds): array
    {
        $body = ['loanIds' => $loanIds];
        return $this->requestJson('POST', '/find/lendbook/reNew', $body, $this->cookie);
    }
}
