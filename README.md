# Trustpilot
用户模拟登录trustpilot后台，抓取trustpilot评论的功能

### 安装
```
composer require crasp/trustpilot
```

### 使用

```
$config = [
    "email"       => "support@dddm.com",
    "password"    => "YocgdddR8x8ddC4cn",
    'client_id'   => 'nZkt0UMZddddd9AOcviMZDmIfiI2L0x',
    'key'         => 'AIzaSyDzjddddbOR_CVaH8EGSvO0',
    'business_id' => '62561753ddd21d2f',
];
$trustpilot = new \Crasp\Trustpilot\Trustpilot($config);
```

#### 拉取最新评论

```
try {
    $result = $trustpilot->search(1, 1, [
    'begin_time' => '2022-10-17 17:53:00',
    'end_time' => '2022-10-27 17:53:00',
    ]);
    print_r($result);
} catch (\Exception $exception) {
    return $exception->getMessage();
}
```

#### 添加回复 && 修改评论

```

$trustpilot = new \Crasp\Trustpilot\Trustpilot($config);
try {
    $result = $trustpilot->reply("aafsa","大大士大夫安抚撒发的啥地方稍等阿萨德");
    print_r($result);
} catch (\Exception $exception) {
    return $exception->getMessage();
}
```
#### 删除回复

```

$trustpilot = new \Crasp\Trustpilot\Trustpilot($config);
try {
    $result = $trustpilot->replyDelete("aafsa","大大士大夫安抚撒发的啥地方稍等阿萨德");
    print_r($result);
} catch (\Exception $exception) {
    return $exception->getMessage();
}
```
