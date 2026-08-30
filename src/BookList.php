<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

class BookList extends Base
{
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
        $body = [
            'page' => $page,
            'rows' => $rows,
            'type' => $type,
        ];
        return $this->requestJson('POST', '/find/bookList/getMyFavBookListPage', $body, $this->cookie);
    }

    /**
     * 获取我的书单
     *
     * @return array
     * @throws Exception
     */
    public function myBookList(): array
    {
        return $this->requestJson('GET', '/find/favorites/list', '', $this->cookie);
    }
}
