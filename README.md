# agora
声网php-SDK

## 要求
* composer
* php >=7.1
* ext-json >=1.0

## 安装
```bash
$ composer require 96qbhy/agora
```

## 使用
```php
require_once 'vendor/autoload.php';

$config = [
    'debug'  => true,
    'id'     => 'your id',
    'secret' => 'your secret',
];

$agora = new \Qbhy\Agora\Agora($config);

var_dump($agora->project->all()); // 获取所有项目
var_dump($agora->usage->get('2019-1-21','2019-1-22',['appid'])); // 获取用量
var_dump($agora->kicking_rule->all()); // 获取所有规则
var_dump($agora->token->buildToken('channel','uid')); // 生成token
```

[96qbhy/agora](https://github.com/qbhy/agora)  
96qbhy@gmail.com