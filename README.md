# tjustb-libsp

天津科技大学（USTB）图书馆 `libsp` 系统的 PHP HTTP Client。

该库对常用检索、热门借阅、最新图书、馆藏详情等接口做了封装，默认请求地址为：

- `https://findtjustb.libsp.cn`

## 功能概览

- 封装常见 `libsp` 接口，统一返回 PHP 数组
- 支持统一检索与高级检索
- 支持热门搜索、热门借阅、最新图书
- 支持图书详情、借阅分析、馆藏信息等二级查询
- 自动初始化 `route` Cookie（用于部分接口请求）

## 环境要求

- PHP `>= 8.0`
- `ext-curl`
- `ext-json`

## 安装

```bash
composer require airmole/tjustb-libsp
```

## 快速开始

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Airmole\TjustbLibsp\Libsp;

$libsp = new Libsp();

// 示例 1：获取热门搜索
$hotKeywords = $libsp->popularSearchSimple();
print_r($hotKeywords);

// 示例 2：统一检索（关键词）
$result = $libsp->search(
    searchFieldContent: '机器学习',
    searchField: 'keyWord',
    page: 1,
    rows: 10
);
print_r($result);

// 示例 3：图书详情（recordId 需从检索结果中获取）
$detail = $libsp->bookDetail(123456);
print_r($detail);
```

## 配置

类 `Base` 会在构造时读取配置并初始化请求环境。
默认配置文件路径：`$_SERVER['DOCUMENT_ROOT'] . '/../.env'`。

可用配置项如下：

| Key | 说明 | 默认值 |
| --- | --- | --- |
| `LIBSP_URL` | libsp 系统地址 | `https://findtjustb.libsp.cn` |
| `LIBSP_PROXY` | cURL 代理地址 | 空 |
| `OPACSYS_TIMEOUT` | 请求超时（秒） | `10` |

`.env` 示例：

```dotenv
LIBSP_URL=https://findtjustb.libsp.cn
LIBSP_PROXY=
OPACSYS_TIMEOUT=10
```

## API 一览（`Airmole\\TjustbLibsp\\Libsp`）

下面列的是对外聚合类 `Libsp` 的主要方法。
所有方法返回值均为 `array`（JSON 解码后），当 HTTP 状态码非 `200` 时会抛出异常。

### 基础与字典

- `dict()` 获取字典数据
- `locationList($page = 1, $rows = 2000, $locationName = '', $campusIds = [], $locationTypeCodes = [], $entrust = 0, $subscribe = 0)` 获取馆藏地点列表
- `conditionList()` 获取检索条件列表
- `opacSearchPara()` 获取 OPAC 检索参数

### 热门与推荐

- `popularSearchSimple()` 获取热门搜索
- `getHotBorrow($page = 1, $rows = 10, $disCode = null, $statRange = 30, $indexFlag = 1, $libCode = '', $sortType = 1, $classNo = '')` 获取热门借阅
- `getNewBook($page = 1, $rows = 10, $time = '2', $docCode = '1')` 获取最新图书

### 检索

- `search(...)` 统一快速检索
- `advancedSearch(...)` 高级检索

检索参数较多，建议按需传参。两个接口文档注释中给出了参数参考：

- `search` 参数参考：[gist](https://gist.github.com/Airmole/07ae4007b7809b06fa1efb3df7ed5f79)
- `advancedSearch` 参数参考：[gist](https://gist.github.com/Airmole/d1d13aa562b7ddc0b96ae77c41eb2646)

### 书目详情与分析

- `bookCountAndCover($recordId, $title = '', $isbn = '')` 获取书目数量和封面
- `docCode($recordId)` 查询 `docCode`
- `bookDetail($recordId)` 获取图书详情
- `tenYearBorrow($recordId)` 最近十年借阅数据
- `borrowAnalysis($recordId)` 借阅分析
- `bookCollectionInfo($recordId, $page = 1, $rows = 10, $callNo = '', $sortType = 0, $isUnify = true, $entrance = null)` 馆藏信息
- `searchDetailAbstract($recordId, $searchField = '', $searchFieldContent = '', $subject = [], $kindNo = null, $page = 1, $rows = 5, $sortField = 'relevance', $sortClause = 'asc')` 详情摘要/相关检索
- `searchAuthorInfo($author = '', $fenlei = '', $size = 10)` 作者信息
- `qrcodeJumpUrl($recordId, $libCode = '')` 获取二维码跳转 URL
- `categoryList($parentClassNo = 'A', $classLev = 1, $classCode = '1')` 分类列表

## 异常处理建议

建议业务侧统一捕获 `\Throwable` 或 `\Exception`，避免请求失败时中断流程：

```php
<?php

use Airmole\TjustbLibsp\Libsp;

try {
    $libsp = new Libsp();
    $result = $libsp->search(searchFieldContent: '数据库');
} catch (\Throwable $e) {
    // 记录日志 + 业务降级
    error_log($e->getMessage());
}
```

## 许可证

本项目使用 [GPL-3.0-or-later](./LICENSE) 许可证。
