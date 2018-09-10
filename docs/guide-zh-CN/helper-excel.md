## ExcelHelper

目录

- 前置说明
- 导出
- 导入

### 前置说明

引入

```
use common\helpers\ExcelHelper;
```

demo

```
// [名称, 字段名, 类型, 类型规则]
$header = [
    ['ID', 'id', 'text'],
    ['openid', 'fans.openid', 'text'],
    ['昵称', 'fans.nickname', 'text'],
    ['关注/扫描', 'type', 'selectd', [1 => '关注', 2 => '扫描']],
    [ '创建时间', 'create_at', 'date', 'Y-m-d'],
];

$list = [
    [
        'id'  => 1,
        'type'  => 1,
        'fans'  => [
            'openid' => '123',
            'nickname' => '昵称',
        ],
        'create_at' => time(),
    ]
];
```

### 导出

```
// 简单使用
ExcelHelper::exportData($list, $header);

// 定制 默认导出xlsx 支持 : xlsx/xls/Html/CSV
ExcelHelper::exportData($list, $header, '测试', 'xlsx');
```

### 导入

```
/**
 * 导入
 *
 * @param $filePath 文件路径
 * @param int $startRow 开始行数 默认 1
 * @return array|bool|mixed
 */
$data = ExcelHelper::getExcelData($filePath, $startRow);
```