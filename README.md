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

var_dump($agora->project->all());
```

[96qbhy/agora](https://github.com/qbhy/agora)  
96qbhy@gmail.com