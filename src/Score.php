<?php

namespace Airmole\TjustbLibsp;

use Airmole\TjustbLibsp\Exception\Exception;

/**
 * 读者积分相关
 */
class Score extends Base
{
    /**
     * 获取读者积分列表
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
        $post = [
            'page' => $page,
            'rows' => $rows,
            'scoreSign' => $scoreSign,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'timeType' => $timeType,
        ];
        $url = '/find/patronScoreDetail/getPatronScoreList';
        $result = $this->httpRequest('POST', $url, $post, $this->cookie);

        if ($result['code'] !== 200) throw new Exception('获取失败：' . $result['code'] . $result['data']);
        return json_decode($result['data'], true);
    }
}