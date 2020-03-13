<?php

declare(strict_types=1);

namespace Lengbin\Hyperf\YiiDb\Rbac;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use Lengbin\Hyperf\YiiDb\Connection;
use Lengbin\YiiDb\Rbac\Exceptions\InvalidArgumentException;
use Lengbin\YiiDb\Rbac\Manager\DbManager;
use Lengbin\YiiDb\Rbac\Manager\PhpManager;
use Lengbin\YiiDb\Rbac\Manager\PhpManagerFile;
use Lengbin\YiiDb\Rbac\RuleFactory\ClassNameRuleFactory;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

class RbacFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $class = null;
        $config = $container->get(ConfigInterface::class)->get('rbac', []);
        $driver = ArrayHelper::getValue($config, 'driver');

        $ruleFactory = new ClassNameRuleFactory();
        $cache = ArrayHelper::getValue($config, 'cache', $container->get(CacheInterface::class));
        $item = ArrayHelper::getValue($config, 'item');;
        $assignment = ArrayHelper::getValue($config, 'assignment');;
        $rule = ArrayHelper::getValue($config, 'rule');

        if ($driver === DbManager::class) {
            $logKey = ArrayHelper::getValue($config, 'log', 'default');
            $itemChild = ArrayHelper::getValue($config, 'itemChild');
            $connection = ArrayHelper::getValue($config, 'connection', Connection::class);
            $db = new $connection($container);
            $logger = $container->get(LoggerFactory::class)->get($logKey);
            $class = new $driver($ruleFactory, $db, $cache, $logger, $item, $itemChild, $assignment, $rule);
        }

        if ($driver === PhpManager::class) {
            $class = new $driver($ruleFactory, $cache, $item, $assignment, $rule);
        }

        if ($driver === PhpManagerFile::class) {
            $directory = ArrayHelper::getValue($config, 'directory');
            $class = new $driver($ruleFactory, $directory, $item, $assignment, $rule);
        }

        if ($class === null) {
            throw new InvalidArgumentException(sprintf('The rabc config [%s] doesn\'t contain a valid driver.', $driver));
        }

        $defaultRoles = ArrayHelper::getValue($config, 'defaultRoles');
        if ($defaultRoles !== null) {
            $class->setDefaultRoles($defaultRoles);
        }
        return $class;
    }
}
