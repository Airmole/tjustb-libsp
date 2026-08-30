# tjustb-libsp

TJUSTB 图书馆 `libsp` 系统的 PHP HTTP Client。

该库对常用检索、热门借阅、最新图书、馆藏详情、统一认证登录、读者积分、书单管理等接口做了封装，默认请求地址为：

- `https://findtjustb.libsp.cn`

## 功能概览

- 封装常见 `libsp` 接口，统一返回 PHP 数组
- 支持统一检索与高级检索
- 支持热门搜索、热门借阅、最新图书
- 支持图书详情、借阅分析、馆藏信息等二级查询
- 支持 SSO 统一认证登录
- 支持读者借阅管理（当前借阅、借阅历史、续借、现刊借阅）
- 支持读者积分查询
- 支持书单管理（我的书单、收藏书单）
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

## API 一览（`Airmole\TjustbLibsp\Libsp`）

所有方法返回值均为 `array`（JSON 解码后），当 HTTP 状态码非 `200` 时会抛出异常。

### 基础与字典

- `dict()` — 获取数据字典定义
- `locationList($page = 1, $rows = 2000, $locationName = '', $campusIds = [], $locationTypeCodes = [], $entrust = 0, $subscribe = 0)` — 获取馆藏地点列表
- `conditionList()` — 获取检索条件列表
- `opacSearchPara()` — 获取 OPAC 检索参数
- `categoryList($parentClassNo = 'A', $classLev = 1, $classCode = '1')` — 获取分类列表

> 不清楚的参数值都可以从数据字典接口中获取定义

### 热门与推荐

- `popularSearchSimple()` — 获取热门搜索
- `disCodeList()` — 获取学科分类（disCode）列表
- `getHotBorrow($page = 1, $rows = 10, $disCode = null, $statRange = 30, $indexFlag = 0, $libCode = '', $sortType = 1, $classNo = '')` — 获取热门借阅
- `getNewBook($page = 1, $rows = 10, $disCode = '', $callNo = '', $sortField = 'in_date', $sortClause = 'desc', $time = '2', $searchWord = '', $docCode = '1', $campusId = [], $libCode = '', $locationId = '', $opacSearchLangCode = '')` — 获取最新图书
- `dailyRecommend()` — 获取每日图书推荐

### 检索

- `search(...)` — 统一快速检索
- `advancedSearch(...)` — 高级检索

检索参数较多，建议按需传参。两个接口文档注释中给出了参数参考：

- `search` 参数参考：[gist](https://gist.github.com/Airmole/07ae4007b7809b06fa1efb3df7ed5f79)
- `advancedSearch` 参数参考：[gist](https://gist.github.com/Airmole/d1d13aa562b7ddc0b96ae77c41eb2646)

### 书目详情与分析

- `bookCountAndCover($recordId, $title = '', $isbn = '')` — 获取书目数量和封面
- `docCode($recordId)` — 查询 `docCode`
- `bookDetail($recordId)` — 获取图书详情
- `tenYearBorrow($recordId)` — 最近十年借阅数据
- `borrowAnalysis($recordId)` — 借阅分析
- `bookCollectionInfo($recordId, $page = 1, $rows = 10, $callNo = '', $sortType = 0, $isUnify = true, $entrance = null)` — 馆藏信息
- `searchDetailAbstract($recordId, $searchField = '', $searchFieldContent = '', $subject = [], $kindNo = null, $page = 1, $rows = 5, $sortField = 'relevance', $sortClause = 'asc')` — 详情摘要/相关检索
- `searchAuthorInfo($author = '', $fenlei = '', $size = 10)` — 作者信息
- `qrcodeJumpUrl($recordId, $libCode = '')` — 获取二维码跳转 URL

### 登录与用户

- `ssoLogin($ticket)` — SSO 统一认证登录（传入 CAS ticket）
- `userInfo($cookie = [])` — 获取用户信息
- `getUserInfo()` — 获取当前用户信息
- `userLimit($userId = '')` — 获取用户限制（到书、超期、未处理行为）

### 借阅管理

- `loanList($searchType = 1, $searchContent = '', $page = 1, $rows = 10, $sortType = 0, $startDate = null, $endDate = null)` — 获取当前借阅图书
- `issueLoanInfoList($searchType = 1, $searchContent = '', $page = 1, $rows = 10, $sortType = 0, $startDate = null, $endDate = null)` — 获取当前借阅现刊
- `loanHistory($searchType = 1, $searchContent = '', $page = 1, $rows = 10, $sortType = 0, $startDate = null, $endDate = null)` — 获取借阅历史
- `issueLoanHistory($searchType = 1, $searchContent = '', $page = 1, $rows = 10, $sortType = 0, $startDate = null, $endDate = null)` — 获取现刊借阅历史
- `renewBooks($loanIds)` — 续借图书（传入借阅 ID 数组）
- `loanChart()` — 获取借阅统计
- `userLoanRules()` — 获取借阅规则

### 积分与书单

- `getPatronScoreList($page = 1, $rows = 10, $scoreSign = null, $startDate = '', $endDate = '', $timeType = null)` — 获取读者积分列表
- `favBookList($page = 1, $rows = 10, $type = 1)` — 获取收藏书单
- `myBookList()` — 获取我的书单

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

## 项目结构

```
src/
├── Base.php          # 基础类：HTTP 请求、Cookie 管理、配置读取
├── Libsp.php         # 对外聚合类（推荐使用）
├── Login.php         # SSO 登录相关
├── Search.php        # 检索与书目详情
├── Popular.php       # 热门搜索、热门借阅、最新图书
├── User.php          # 用户信息与借阅管理
├── BookList.php      # 书单管理
├── Score.php         # 读者积分
├── Misc.php          # 数据字典
└── Exception/
    └── Exception.php # 自定义异常
```

## 许可证

本项目使用 [GPL-3.0-or-later](./LICENSE) 许可证。
