<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class BookList extends Base
{
    /**
     * 获取收藏书单
     * @param int $page 页码
     * @param int $rows 每页条数
     * @param int $type 书单类型：0-读者发布；1-公共书单
     * @return array
     * @throws Exception
     */
    public function favBookList(int $page = 1, int $rows = 10, int $type = 1): array
    {
        $body = [
            'page' => $page,
            'rows' => $rows,
            'type' => $type,
        ];
        $url = '/find/bookList/getMyFavBookListPage';
        $result = $this->httpRequest('POST', $url, $body, $this->cookie);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

    /**
     * 获取我的书单
     * @return array
     * @throws Exception
     */
    public function myBookList(): array
    {
        $url = '/find/favorites/list';
        $result = $this->httpRequest('GET', $url, '', $this->cookie);
        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }

}