<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class Libsp extends Base
{
    /** @var array|null 服务实例缓存 */
    private ?array $services = null;

    /**
     * 获取（并缓存）服务实例
     *
     * @template T of Base
     * @param class-string<T> $class
     * @return T
     */
    private function service(string $class): Base
    {
        if ($this->services === null) {
            $this->services = [];
        }
        if (!isset($this->services[$class])) {
            $service = new $class();
            $service->libspUrl = $this->libspUrl;
            $service->proxy = $this->proxy;
            $service->configPath = $this->configPath;
            $service->cookie = $this->cookie;
            $service->cookieArray = $this->cookieArray;
            $service->chaoxingCookieArray = $this->chaoxingCookieArray;
            $this->services[$class] = $service;
        }
        return $this->services[$class];
    }

    // ────────────────────────── 数据字典 ──────────────────────────

    /**
     * 获取数据字典
     *
     * @return array
     * @throws Exception
     */
    public function dict(): array
    {
        return $this->service(Misc::class)->dict();
    }

    // ────────────────────────── 检索与馆藏 ──────────────────────────

    /**
     * 获取馆藏地点列表
     *
     * @return array
     * @throws Exception
     */
    public function locationList(
        int $page = 1,
        int $rows = 2000,
        string $locationName = '',
        array $campusIds = [],
        array $locationTypeCodes = [],
        int $entrust = 0,
        int $subscribe = 0
    ): array
    {
        return $this->service(Search::class)->locationList(
            $page, $rows, $locationName, $campusIds, $locationTypeCodes, $entrust, $subscribe
        );
    }

    /**
     * 获取检索条件列表
     *
     * @return array
     * @throws Exception
     */
    public function conditionList(): array
    {
        return $this->service(Search::class)->conditionList();
    }

    /**
     * 获取 OPAC 检索参数
     *
     * @return array
     * @throws Exception
     */
    public function opacSearchPara(): array
    {
        return $this->service(Search::class)->opacSearchPara();
    }

    /**
     * 统一快速检索
     *
     * @return array
     * @throws Exception
     */
    public function search(
        string $searchFieldContent = '',
        string $searchField = 'keyWord',
        int $page = 1,
        int $rows = 10,
        array $docCode = [],
        array $litCode = [],
        string $matchMode = '2',
        array $resourceType = [],
        array $subject = [],
        array $discode1 = [],
        array $publisher = [],
        array $libCode = [],
        array $locationId = [],
        array $eCollectionIds = [],
        array $neweCollectionIds = [],
        array $curLocationId = [],
        array $campusId = [],
        array $kindNo = [],
        array $collectionName = [],
        array $author = [],
        array $langCode = [],
        array $countryCode = [],
        string $publishBegin = null,
        string $publishEnd = null,
        array $coreInclude = [],
        array $ddType = [],
        array $verifyStatus = [],
        array $group = [],
        string $sortField = 'relevance',
        string $sortClause = 'asc',
        mixed $onlyOnShelf = null,
        mixed $searchItems = null,
        array $newCoreInclude = [],
        array $customSub = [],
        array $customSub0 = [],
        int $indexSearch = 1
    ): array
    {
        return $this->service(Search::class)->search(
            $searchFieldContent, $searchField, $page, $rows,
            $docCode, $litCode, $matchMode, $resourceType, $subject, $discode1,
            $publisher, $libCode, $locationId, $eCollectionIds, $neweCollectionIds,
            $curLocationId, $campusId, $kindNo, $collectionName, $author,
            $langCode, $countryCode, $publishBegin, $publishEnd, $coreInclude,
            $ddType, $verifyStatus, $group, $sortField, $sortClause,
            $onlyOnShelf, $searchItems, $newCoreInclude, $customSub, $customSub0,
            $indexSearch
        );
    }

    /**
     * 高级检索
     *
     * @return array
     * @throws Exception
     */
    public function advancedSearch(
        array $searchItems = [],
        int $page = 1,
        int $rows = 10,
        array $docCode = [],
        array $litCode = [],
        string $matchMode = '2',
        array $resourceType = [],
        array $subject = [],
        array $discode1 = [],
        array $publisher = [],
        array $libCode = [],
        array $locationId = [],
        array $eCollectionIds = [],
        array $neweCollectionIds = [],
        array $curLocationId = [],
        array $campusId = [],
        array $kindNo = [],
        array $collectionName = [],
        array $author = [],
        array $langCode = [],
        array $countryCode = [],
        string $publishBegin = null,
        string $publishEnd = null,
        array $coreInclude = [],
        array $ddType = [],
        array $verifyStatus = [],
        array $group = [],
        string $sortField = 'relevance',
        string $sortClause = 'asc',
        mixed $onlyOnShelf = null,
        string $searchFieldContent = '',
        string $searchField = 'keyWord',
        bool $isOpen = false
    ): array
    {
        return $this->service(Search::class)->advancedSearch(
            $searchItems, $page, $rows,
            $docCode, $litCode, $matchMode, $resourceType, $subject, $discode1,
            $publisher, $libCode, $locationId, $eCollectionIds, $neweCollectionIds,
            $curLocationId, $campusId, $kindNo, $collectionName, $author,
            $langCode, $countryCode, $publishBegin, $publishEnd, $coreInclude,
            $ddType, $verifyStatus, $group, $sortField, $sortClause,
            $onlyOnShelf, $searchFieldContent, $searchField, $isOpen
        );
    }

    /**
     * 获取书目数量和封面
     *
     * @return array
     * @throws Exception
     */
    public function bookCountAndCover(
        int $recordId,
        string $title = '',
        string $isbn = '',
    ): array
    {
        return $this->service(Search::class)->bookCountAndCover($recordId, $title, $isbn);
    }

    /**
     * 查询 docCode
     *
     * @return array
     * @throws Exception
     */
    public function docCode(int|string $recordId): array
    {
        return $this->service(Search::class)->docCode($recordId);
    }

    /**
     * 获取图书详情
     *
     * @return array
     * @throws Exception
     */
    public function bookDetail(int|string $recordId): array
    {
        return $this->service(Search::class)->bookDetail($recordId);
    }

    /**
     * 最近十年借阅数量
     *
     * @return array
     * @throws Exception
     */
    public function tenYearBorrow(int|string $recordId): array
    {
        return $this->service(Search::class)->tenYearBorrow($recordId);
    }

    /**
     * 借阅分析
     *
     * @return array
     * @throws Exception
     */
    public function borrowAnalysis(int|string $recordId): array
    {
        return $this->service(Search::class)->borrowAnalysis($recordId);
    }

    /**
     * 获取图书馆藏信息
     *
     * @return array
     * @throws Exception
     */
    public function bookCollectionInfo(
        int|string $recordId,
        int $page = 1,
        int $rows = 10,
        string $callNo = '',
        int $sortType = 0,
        bool $isUnify = true,
        mixed $entrance = null
    ): array
    {
        return $this->service(Search::class)->bookCollectionInfo(
            $recordId, $page, $rows, $callNo, $sortType, $isUnify, $entrance
        );
    }

    /**
     * 搜索获取图书详情摘要 / 获取相关借阅图书
     *
     * @return array
     * @throws Exception
     */
    public function searchDetailAbstract(
        int|string $recordId,
        string $searchField = '',
        string $searchFieldContent = '',
        array $subject = [],
        mixed $kindNo = null,
        int $page = 1,
        int $rows = 5,
        string $sortField = 'relevance',
        string $sortClause = 'asc'
    ): array
    {
        return $this->service(Search::class)->searchDetailAbstract(
            $recordId, $searchField, $searchFieldContent,
            $subject, $kindNo, $page, $rows, $sortField, $sortClause
        );
    }

    /**
     * 获取作者信息、论文期刊
     *
     * @return array
     * @throws Exception
     */
    public function searchAuthorInfo(
        string $author = '',
        string $fenlei = '',
        int $size = 10
    ): array
    {
        return $this->service(Search::class)->searchAuthorInfo($author, $fenlei, $size);
    }

    /**
     * 获取二维码跳转 URL
     *
     * @return array
     * @throws Exception
     */
    public function qrcodeJumpUrl(int|string $recordId, string $libCode = ''): array
    {
        return $this->service(Search::class)->qrcodeJumpUrl($recordId, $libCode);
    }

    /**
     * 获取分类列表
     *
     * @return array
     * @throws Exception
     */
    public function categoryList(
        string $parentClassNo = 'A',
        int $classLev = 1,
        string $classCode = '1'
    ): array
    {
        return $this->service(Search::class)->categoryList($parentClassNo, $classLev, $classCode);
    }

    // ────────────────────────── 热门与推荐 ──────────────────────────

    /**
     * 获取热门搜索
     *
     * @return array
     * @throws Exception
     */
    public function popularSearchSimple(): array
    {
        return $this->service(Popular::class)->popularSearchSimple();
    }

    /**
     * 获取学科分类（disCode）列表
     *
     * @return array
     * @throws Exception
     */
    public function disCodeList(): array
    {
        return $this->service(Popular::class)->disCodeList();
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
        return $this->service(Popular::class)->getHotBorrow(
            $page, $rows, $disCode, $statRange, $indexFlag, $libCode, $sortType, $classNo
        );
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
        return $this->service(Popular::class)->getNewBook(
            $page, $rows, $disCode, $callNo, $sortField, $sortClause,
            $time, $searchWord, $docCode, $campusId, $libCode, $locationId,
            $opacSearchLangCode
        );
    }

    // ────────────────────────── 登录与用户 ──────────────────────────

    /**
     * SSO 登录
     *
     * @param string $ticket 登录凭证
     * @return array
     * @throws Exception
     */
    public function ssoLogin(string $ticket): array
    {
        return $this->service(Login::class)->ssoLogin($ticket);
    }

    /**
     * 获取用户信息（登录后）
     *
     * @param array $cookie Cookie 数组
     * @return array
     * @throws Exception
     */
    public function userInfo(array $cookie = []): array
    {
        return $this->service(Login::class)->userInfo($cookie);
    }

    /**
     * 获取用户限制数据
     *
     * @param string $userId 用户ID
     * @return array
     * @throws Exception
     */
    public function userLimit(string $userId = ''): array
    {
        return $this->service(User::class)->userLimit($userId);
    }

    /**
     * 获取用户信息
     *
     * @return array
     * @throws Exception
     */
    public function getUserInfo(): array
    {
        return $this->service(User::class)->getUserInfo();
    }

    /**
     * 获取每日图书推荐
     *
     * @return array
     * @throws Exception
     */
    public function dailyRecommend(): array
    {
        return $this->service(User::class)->dailyRecommend();
    }

    /**
     * 获取借阅统计
     *
     * @return array
     * @throws Exception
     */
    public function loanChart(): array
    {
        return $this->service(User::class)->loanChart();
    }

    /**
     * 获取借阅规则
     *
     * @return array
     * @throws Exception
     */
    public function userLoanRules(): array
    {
        return $this->service(User::class)->userLoanRules();
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
        return $this->service(User::class)->loanList(
            $searchType, $searchContent, $page, $rows, $sortType, $startDate, $endDate
        );
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
        return $this->service(User::class)->issueLoanInfoList(
            $searchType, $searchContent, $page, $rows, $sortType, $startDate, $endDate
        );
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
        return $this->service(User::class)->loanHistory(
            $searchType, $searchContent, $page, $rows, $sortType, $startDate, $endDate
        );
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
        return $this->service(User::class)->issueLoanHistory(
            $searchType, $searchContent, $page, $rows, $sortType, $startDate, $endDate
        );
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
        return $this->service(User::class)->renewBooks($loanIds);
    }

    // ────────────────────────── 积分 ──────────────────────────

    /**
     * 获取读者积分列表
     *
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param int|null $scoreSign 积分类型：null-全部；0-加分；1-减分
     * @param string $startDate 开始日期
     * @param string $endDate 结束日期
     * @param string|null $timeType 时间类型
     * @return array
     * @throws Exception
     */
    public function getPatronScoreList(
        int $page = 1,
        int $rows = 10,
        int $scoreSign = null,
        string $startDate = '',
        string $endDate = '',
        string $timeType = null,
    ): array
    {
        return $this->service(Score::class)->getPatronScoreList(
            $page, $rows, $scoreSign, $startDate, $endDate, $timeType
        );
    }

    // ────────────────────────── 书单 ──────────────────────────

    /**
     * 获取收藏书单
     *
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param int $type 书单类型：0-读者发布；1-公共书单
     * @return array
     * @throws Exception
     */
    public function favBookList(int $page = 1, int $rows = 10, int $type = 1): array
    {
        return $this->service(BookList::class)->favBookList($page, $rows, $type);
    }

    /**
     * 获取我的书单
     *
     * @return array
     * @throws Exception
     */
    public function myBookList(): array
    {
        return $this->service(BookList::class)->myBookList();
    }
}
