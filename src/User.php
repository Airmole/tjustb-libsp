<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class User extends Base
{
    /**
     * @param string $userId
     * @return array
     * @throws Exception
     */
    public function userLimit(string $userId = ''): array
    {
        $query = ['userId' => $userId];
        $url = self::DEFAULT_LIBSP_URL . '/find/user/userLimit?' . http_build_query($query);
        $result = $this->httpRequest('GET', $url, '', $this->cookie);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getUserInfo(): array
    {
        $url = '/find/userInfo/getUserInfo';

        $result = $this->httpRequest('GET', $url, '', $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取每日推荐图书
     * @return array
     * @throws Exception
     */
    public function dailyRecommend(): array
    {
        $url = '/find/subscribe/dailyRecommend';
        $result = $this->httpRequest('GET', $url, '', $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取借阅统计
     * @return array
     * @throws Exception
     */
    public function loanChart(): array
    {
        $url = '/find/loanInfo/loanChart';
        $result = $this->httpRequest('POST', $url, '{}', $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取借阅规则
     * @return array
     * @throws Exception
     */
    public function userLoanRules(): array
    {
        $url = '/find/userInfo/UserLoanRules';
        $result = $this->httpRequest('POST', $url, '{}', $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取当前借阅图书
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
            'searchType' => $searchType,
            'searchContent' => $searchContent,
            'page' => $page,
            'rows' => $rows,
            'sortType' => $sortType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        $url = '/find/loanInfo/loanList';
        $result = $this->httpRequest('POST', $url, $body, $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取当前借阅现刊
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
            'searchType' => $searchType,
            'searchContent' => $searchContent,
            'page' => $page,
            'rows' => $rows,
            'sortType' => $sortType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        $url = '/find/loanInfo/getIssueLoanInfoList';
        $result = $this->httpRequest('POST', $url, $body, $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取借阅历史
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
            'searchType' => $searchType,
            'searchContent' => $searchContent,
            'page' => $page,
            'rows' => $rows,
            'sortType' => $sortType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        $url = '/find/loanInfo/loanHistoryList';
        $result = $this->httpRequest('POST', $url, $body, $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取现刊借阅历史
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
            'searchType' => $searchType,
            'searchContent' => $searchContent,
            'page' => $page,
            'rows' => $rows,
            'sortType' => $sortType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        $url = '/find/loanInfo/getIssueLoanHistory';
        $result = $this->httpRequest('POST', $url, $body, $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 续借
     * @param array $loanIds 借阅ID数组
     * @return array
     * @throws Exception
     */
    public function renewBooks(array $loanIds): array
    {
        $body = [
            'loanIds' => $loanIds,
        ];

        $url = '/find/lendbook/reNew';
        $result = $this->httpRequest('POST', $url, $body, $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

}