<p align="center">
    <a href="https://hyperf.io/" target="_blank">
        <img src="https://hyperf.oss-cn-hangzhou.aliyuncs.com/hyperf.png" height="100px">
    </a>
    <h1 align="center">Hyperf yii rbac</h1>
    <br>
</p>

If You Like This Please Give Me Star

Install
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require lengbin/hyperf-yii-rbac
```

or add

```
"lengbin/hyperf-yii-rbac": "*"
```
to the require section of your `composer.json` file.


Request [详情](https://github.com/ice-leng/jwt)
-------
```
"lengbin/jwt": "dev-master"
```

Configs
-----
``` php
    /config/autoload/rbac.php
    return [
        'driver'       => \Lengbin\YiiDb\Rbac\Manager\DbManager::class,
    ];
```


Publish
-------
```php
      
php ./bin/hyperf.php vendor:publish lengbin/hyperf-yii-rbac

```
