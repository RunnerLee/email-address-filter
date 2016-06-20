# email-address-filter
邮箱地址检查

检查邮箱地址的格式，并使用已经检查过MX解析的域名列表生成字典检查邮箱地址的域名是否有效（白名单）。
如果域名不在字典中，则查询域名是否有mx解析记录，如果有则将改域名缓存进临时表。

## Usage

#### 生成字典
```

\Runner\EmailAddressFilter\Builder::build(__DIR__ . '/data/domain.list', __DIR__ . '/data/dict.list');

```

#### 调用
```

$filter = new \Runner\EmailAddressFilter\EmailAddressFilter(__DIR__ . '/data/dict.list', __DIR__ . '/data/tempTable.list');

var_dump($filter->filterWithQueryDns('runnerleer@gmail.com'));

```