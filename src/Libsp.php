<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbOpacsys\Exception\Exception;

class Libsp extends Base
{
    /**
     * 获取数据字典
     * @see https://gist.github.com/Airmole/147443e2f1ed0222769f033794e6fb09
     * @return array
     * @throws Exception
     */
    public function dict(): array
    {
        $misc = new Misc();
        return $misc->dict();
    }

    /**
     * @param int $page
     * @param int $rows
     * @param string $locationName
     * @param array $campusIds
     * @param array $locationTypeCodes
     * @param int $entrust
     * @param int $subscribe
     * @return mixed
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
        $search = new Search();
        return $search->locationList($page, $rows, $locationName, $campusIds, $locationTypeCodes, $entrust, $subscribe);
    }

    /**
     * 获取检索条件列表
     * @return array
     * @throws Exception
     */
    public function conditionList(): array
    {
        $search = new Search();
        return $search->conditionList();
    }

    /**
     * 获取热门搜索
     * @return array
     * @throws Exception
     */
    public function popularSearchSimple(): array
    {
        $popular = new Popular();
        return $popular->popularSearchSimple();
    }

    /**
     * 获取disCode列表
     * disCode 疑似学科分类
     * @return array
     * @throws Exception
     */
    public function disCodeList(): array
    {
        $popular = new Popular();
        return $popular->disCodeList();
    }

    /**
     * 获取热门借阅
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param string|null $disCode 学科分类，参数值取自disCodeList()
     * @param int $statRange 统计范围天数
     * @param int $indexFlag
     * @param string $libCode 图书馆代码，本校值为20096000001，空字符串匹配全部
     * @param int $sortType 排序方式，1-按借阅次数，2-按借阅比
     * @param string $classNo
     * @return array
     * @throws Exception
     */
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
        $popular = new Popular();
        return $popular->getHotBorrow($page, $rows, $disCode, $statRange, $indexFlag, $libCode, $sortType, $classNo);
    }

    /**
     * 获取最新图书
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param string $disCode 学科分类号
     * @param string $callNo 中图分类号
     * @param string $sortField 排序字段
     * @param string $sortClause 排序规则
     * @param string $time 时间范围：1-近一周，2-近一月，3-近两月，4-近三月，5-近半年，6-近一年，7-近两年
     * @param string $searchWord
     * @param string $docCode 文档类型，1-图书，2-期刊...详见dict接口
     * @param array $campusId 校区ID筛选
     * @param string $libCode 图书馆筛选
     * @param string $locationId 藏书地筛选
     * @param string $opacSearchLangCode
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
        string $time = "2",
        string $searchWord = '',
        string $docCode = "1",
        array $campusId = [],
        string $libCode = '',
        string $locationId = '',
        string $opacSearchLangCode = ''
    ): array
    {
        $popular = new Popular();
        return $popular->getNewBook(
            $page,
            $rows,
            $disCode,
            $callNo,
            $sortField,
            $sortClause,
            $time,
            $searchWord,
            $docCode,
            $campusId,
            $libCode,
            $locationId,
            $opacSearchLangCode
        );
    }

    /**
     * 获取搜索参数（OPAC）
     * @return array
     * @throws Exception
     */
    public function opacSearchPara(): array
    {
        $search = new Search();
        return $search->opacSearchPara();
    }

    /**
     * 统一快速检索
     * @see https://gist.github.com/Airmole/07ae4007b7809b06fa1efb3df7ed5f79
     * @param string $searchFieldContent
     * @param string $searchField
     * @param int $page
     * @param int $rows
     * @param array $docCode
     * @param array $litCode
     * @param string $matchMode
     * @param array $resourceType
     * @param array $subject
     * @param array $discode1
     * @param array $publisher
     * @param array $libCode
     * @param array $locationId
     * @param array $eCollectionIds
     * @param array $neweCollectionIds
     * @param array $curLocationId
     * @param array $campusId
     * @param array $kindNo
     * @param array $collectionName
     * @param array $author
     * @param array $langCode
     * @param array $countryCode
     * @param string|null $publishBegin
     * @param string|null $publishEnd
     * @param array $coreInclude
     * @param array $ddType
     * @param array $verifyStatus
     * @param array $group
     * @param string $sortField
     * @param string $sortClause
     * @param mixed|null $onlyOnShelf
     * @param mixed|null $searchItems
     * @param array $newCoreInclude
     * @param array $customSub
     * @param array $customSub0
     * @param int $indexSearch
     * @return mixed
     * @throws Exception
     */
    public function search(
        string $searchFieldContent = '',
        string $searchField = 'keyWord',
        int $page = 1,
        int $rows = 10,
        array $docCode = [],
        array $litCode = [],
        string $matchMode = "2",
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
        $search = new Search();
        return $search->search(
            $searchFieldContent,
            $searchField,
            $page,
            $rows,
            $docCode,
            $litCode,
            $matchMode,
            $resourceType,
            $subject,
            $discode1,
            $publisher,
            $libCode,
            $locationId,
            $eCollectionIds,
            $neweCollectionIds,
            $curLocationId,
            $campusId,
            $kindNo,
            $collectionName,
            $author,
            $langCode,
            $countryCode,
            $publishBegin,
            $publishEnd,
            $coreInclude,
            $ddType,
            $verifyStatus,
            $group,
            $sortField,
            $sortClause,
            $onlyOnShelf,
            $searchItems,
            $newCoreInclude,
            $customSub,
            $customSub0,
            $indexSearch
        );
    }

    /**
     * 高级检索
     * @see https://gist.github.com/Airmole/d1d13aa562b7ddc0b96ae77c41eb2646
     * @param array $searchItems
     * @param int $page
     * @param int $rows
     * @param array $docCode
     * @param array $litCode
     * @param string $matchMode
     * @param array $resourceType
     * @param array $subject
     * @param array $discode1
     * @param array $publisher
     * @param array $libCode
     * @param array $locationId
     * @param array $eCollectionIds
     * @param array $neweCollectionIds
     * @param array $curLocationId
     * @param array $campusId
     * @param array $kindNo
     * @param array $collectionName
     * @param array $author
     * @param array $langCode
     * @param array $countryCode
     * @param string|null $publishBegin
     * @param string|null $publishEnd
     * @param array $coreInclude
     * @param array $ddType
     * @param array $verifyStatus
     * @param array $group
     * @param string $sortField
     * @param string $sortClause
     * @param mixed|null $onlyOnShelf
     * @param string $searchFieldContent
     * @param string $searchField
     * @param bool $isOpen
     * @return array
     * @throws Exception
     */
    public function advancedSearch(
        array $searchItems = [],
        int $page = 1,
        int $rows = 10,
        array $docCode = [],
        array $litCode = [],
        string $matchMode = "2",
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
        $search = new Search();
        return $search->advancedSearch(
            $searchItems,
            $page,
            $rows,
            $docCode,
            $litCode,
            $matchMode,
            $resourceType,
            $subject,
            $discode1,
            $publisher,
            $libCode,
            $locationId,
            $eCollectionIds,
            $neweCollectionIds,
            $curLocationId,
            $campusId,
            $kindNo,
            $collectionName,
            $author,
            $langCode,
            $countryCode,
            $publishBegin,
            $publishEnd,
            $coreInclude,
            $ddType,
            $verifyStatus,
            $group,
            $sortField,
            $sortClause,
            $onlyOnShelf,
            $searchFieldContent,
            $searchField,
            $isOpen
        );
    }

    /**
     * 获取书目数量和封面
     * @param int $recordId 记录ID
     * @param string $title 图书标题
     * @param string $isbn ISBN码
     * @return array
     * @throws Exception
     */
    public function bookCountAndCover(
        int $recordId,
        string $title = '',
        string $isbn = '',
    ): array
    {
        $search = new Search();
        return $search->bookCountAndCover($recordId, $title, $isbn);
    }

    /**
     * 查询docCode
     * @param int|string $recordId 图书记录ID
     * @return array
     * @throws Exception
     */
    public function docCode(int|string $recordId): array
    {
        $search = new Search();
        return $search->docCode($recordId);
    }

    /**
     * 获取图书详情
     * @param int|string $recordId
     * @return mixed
     * @throws Exception
     */
    public function bookDetail(int|string $recordId): array
    {
        $search = new Search();
        return $search->bookDetail($recordId);
    }

    /**
     * 最近十年借阅数量
     * @param int|string $recordId
     * @return array
     * @throws Exception
     */
    public function tenYearBorrow(int|string $recordId): array
    {
        $search = new Search();
        return $search->tenYearBorrow($recordId);
    }

    /**
     * 借阅分析
     * @param int|string $recordId
     * @return array
     * @throws Exception
     */
    public function borrowAnalysis(int|string $recordId): array
    {
        $search = new Search();
        return $search->borrowAnalysis($recordId);
    }

    /**
     * 获取图书馆藏信息
     * @param int|string $recordId 图书记录ID
     * @param int $page 页码
     * @param int $rows 每页数量
     * @param string $callNo 索书号
     * @param int $sortType 排序方式
     * @param bool $isUnify
     * @param mixed|null $entrance
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
        $search = new Search();
        return $search->bookCollectionInfo(
            $recordId,
            $page,
            $rows,
            $callNo,
            $sortType,
            $isUnify,
            $entrance
        );
    }

    /**
     * 搜索获取图书详情摘要 || 获取相关借阅图书
     * @param int|string $recordId 图书记录ID
     * @param string $searchField
     * @param string $searchFieldContent
     * @param array $subject
     * @param mixed|null $kindNo
     * @param int $page
     * @param int $rows
     * @param string $sortField
     * @param string $sortClause
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
        $search = new Search();
        return $search->searchDetailAbstract(
            $recordId,
            $searchField,
            $searchFieldContent,
            $subject,
            $kindNo,
            $page,
            $rows,
            $sortField,
            $sortClause
        );
    }

    /**
     * 获取作者信息、论文期刊
     * @param string $author
     * @param string $fenlei
     * @param int $size
     * @return array
     * @throws Exception
     */
    public function searchAuthorInfo(
        string $author = '',
        string $fenlei = '',
        int $size = 10
    ): array
    {
        $search = new Search();
        return $search->searchAuthorInfo($author, $fenlei, $size);
    }

    /**
     * 获取跳转URL二维码
     * @param int|string $recordId 图书记录ID
     * @param string $libCode
     * @return array
     * @throws Exception
     */
    public function qrcodeJumpUrl(int|string $recordId, string $libCode = ''): array
    {
        $search = new Search();
        return $search->qrcodeJumpUrl($recordId, $libCode);
    }

    /**
     * 分类列表
     * @param string $parentClassNo
     * @param int $classLev
     * @param string $classCode
     * @return array
     * @throws Exception
     */
    public function categoryList(
        string $parentClassNo = 'A',
        int $classLev = 1,
        string $classCode = '1'
    ): array
    {
        $search = new Search();
        return $search->categoryList($parentClassNo, $classLev, $classCode);
    }

}