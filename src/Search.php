<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbOpacsys\Exception\Exception;

class Search extends Base
{
    public function opacSearchPara()
    {
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', '/find/groupResource/getFindOpacSearchFieldParaList?', '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function locationList(
        int    $page = 1,
        int    $rows = 2000,
        string $locationName = '',
        array  $campusIds = [],
        array  $locationTypeCodes = [],
        int    $entrust = 0,
        int    $subscribe = 0
    ): array
    {
        $body = [
            'page'              => $page,
            'rows'              => $rows,
            'locationName'      => $locationName,
            'campusIds'         => $campusIds,
            'locationTypeCodes' => $locationTypeCodes,
            'entrust'           => $entrust,
            'subscribe'         => $subscribe
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/location/list', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function conditionList(): array
    {
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', '/find/category/getConditionList', '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function search(
        string $searchFieldContent = '',
        string $searchField = 'keyWord',
        int    $page = 1,
        int    $rows = 10,
        array  $docCode = [],
        array  $litCode = [],
        string $matchMode = "2",
        array  $resourceType = [],
        array  $subject = [],
        array  $discode1 = [],
        array  $publisher = [],
        array  $libCode = [],
        array  $locationId = [],
        array  $eCollectionIds = [],
        array  $neweCollectionIds = [],
        array  $curLocationId = [],
        array  $campusId = [],
        array  $kindNo = [],
        array  $collectionName = [],
        array  $author = [],
        array  $langCode = [],
        array  $countryCode = [],
        string $publishBegin = null,
        string $publishEnd = null,
        array  $coreInclude = [],
        array  $ddType = [],
        array  $verifyStatus = [],
        array  $group = [],
        string $sortField = 'relevance',
        string $sortClause = 'asc',
        mixed  $onlyOnShelf = null,
        mixed  $searchItems = null,
        array  $newCoreInclude = [],
        array  $customSub = [],
        array  $customSub0 = [],
        int    $indexSearch = 1
    )
    {
        $body = [
            'searchFieldContent' => $searchFieldContent,
            'searchField'        => $searchField,
            'page'               => $page,
            'rows'               => $rows,
            'docCode'            => $docCode,
            'litCode'            => $litCode,
            'matchMode'          => $matchMode,
            'resourceType'       => $resourceType,
            'subject'            => $subject,
            'discode1'           => $discode1,
            'publisher'          => $publisher,
            'libCode'            => $libCode,
            'locationId'         => $locationId,
            'eCollectionIds'     => $eCollectionIds,
            'neweCollectionIds'  => $neweCollectionIds,
            'curLocationId'      => $curLocationId,
            'campusId'           => $campusId,
            'kindNo'             => $kindNo,
            'collectionName'     => $collectionName,
            'author'             => $author,
            'langCode'           => $langCode,
            'countryCode'        => $countryCode,
            'publishBegin'       => $publishBegin,
            'publishEnd'         => $publishEnd,
            'coreInclude'        => $coreInclude,
            'ddType'             => $ddType,
            'verifyStatus'       => $verifyStatus,
            'group'              => $group,
            'sortField'          => $sortField,
            'sortClause'         => $sortClause,
            'onlyOnShelf'        => $onlyOnShelf,
            'searchItems'        => $searchItems,
            'newCoreInclude'     => $newCoreInclude,
            'customSub'          => $customSub,
            'customSub0'         => $customSub0,
            'indexSearch'        => $indexSearch
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/unify/search', $body, '', $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

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
        $body = [
            'searchItems'        => $searchItems,
            'page'               => $page,
            'rows'               => $rows,
            'docCode'            => $docCode,
            'litCode'            => $litCode,
            'matchMode'          => $matchMode,
            'resourceType'       => $resourceType,
            'subject'            => $subject,
            'discode1'           => $discode1,
            'publisher'          => $publisher,
            'libCode'            => $libCode,
            'locationId'         => $locationId,
            'eCollectionIds'     => $eCollectionIds,
            'neweCollectionIds'  => $neweCollectionIds,
            'curLocationId'      => $curLocationId,
            'campusId'           => $campusId,
            'kindNo'             => $kindNo,
            'collectionName'     => $collectionName,
            'author'             => $author,
            'langCode'           => $langCode,
            'countryCode'        => $countryCode,
            'publishBegin'       => $publishBegin,
            'publishEnd'       => $publishEnd,
            'coreInclude'        => $coreInclude,
            'ddType'             => $ddType,
            'verifyStatus'       => $verifyStatus,
            'group'              => $group,
            'sortField'          => $sortField,
            'sortClause'         => $sortClause,
            'onlyOnShelf'        => $onlyOnShelf,
            'searchFieldContent' => $searchFieldContent,
            'searchField'        => $searchField,
            'isOpen'            => $isOpen
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/unify/advancedSearch', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function bookCountAndCover(
        int    $recordId,
        string $title = '',
        string $isbn = '',
    ): array
    {
        $body = [
            'recordId' => $recordId,
            'title'    => $title,
            'isbn'     => $isbn
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/unify/getPItemAndOnShelfCountAndDuxiuImageUrl', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function docCode(int|string $recordId): array
    {
        $query = http_build_query(['recordId' => $recordId]);
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', "/find/searchResultDetail/getDocCode?{$query}", '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function bookDetail(int|string $recordId): array
    {
        $query = http_build_query(['recordId' => $recordId]);
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', "/find/searchResultDetail/getDetail?{$query}", '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function tenYearBorrow(int|string $recordId): array
    {
        $query = http_build_query(['recordId' => $recordId]);
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', "/find/loanInfo/getNearTenYearLoan?{$query}", '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function borrowAnalysis(int|string $recordId): array
    {
        $query = http_build_query(['recordId' => $recordId]);
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', "/find/searchResultDetail/getLoanAnalysis?{$query}", '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function bookCollectionInfo(
        int|string $recordId,
        int        $page = 1,
        int        $rows = 10,
        string     $callNo = '',
        int        $sortType = 0,
        bool       $isUnify = true,
        mixed      $entrance = null
    ): array
    {
        $body = [
            'recordId' => $recordId,
            'page'     => $page,
            'rows'     => $rows,
            'callNo'   => $callNo,
            'sortType' => $sortType,
            'isUnify'  => $isUnify,
            'entrance' => $entrance
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/physical/groupitems', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

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
        $body = [
            'excludeFieldContent'              => $recordId,
            'excludeField' => 'record_id',
            'page'                  => $page,
            'rows'                  => $rows,
            'sortField' => $sortField,
            'sortClause' => $sortClause,
        ];
        if (!empty($searchField)) $body['searchField'] = $searchField;
        if (!empty($searchFieldContent)) $body['searchFieldContent'] = $searchFieldContent;
        if (!empty($subject)) {
            $body['subject'] = $subject;
            $body['kindNo'] = $kindNo;
        }

        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/unify/searchForDetail', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function searchAuthorInfo(
        string $author = '',
        string $fenlei = '',
        int $size = 10
    ): array
    {
        $query = http_build_query([
            'author' => $author,
            'fenlei' => $fenlei,
            'size' => $size
        ]);
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', "/find/searchResultDetail/getAuthorInfo?{$query}", '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function qrcodeJumpUrl(int|string $recordId, string $libCode = ''): array
    {
        $query = http_build_query(['recordId' => $recordId]);
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('GET', "/find/searchResultDetail/getQrCodeJumpUrl?{$query}", '', $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    public function categoryList(
        string $parentClassNo = 'A',
        int $classLev = 1,
        string $classCode = '1'
    ): array
    {
        $body = [
            'parentClassNo' => $parentClassNo,
            'classLev' => $classLev,
            'classCode' => $classCode
        ];
        $headers = ["Referer: {$this->libspUrl}/"];
        $result = $this->httpRequest('POST', '/find/category/classifica/list', $body, $this->cookie, $headers);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

}