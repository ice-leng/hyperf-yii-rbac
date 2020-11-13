<?php

declare(strict_types=1);

namespace Lengbin\Hyperf\YiiSoft\Rbac;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use Lengbin\YiiSoft\Rbac\Exceptions\InvalidArgumentException;
use Lengbin\YiiSoft\Rbac\Manager\DbManager;
use Lengbin\YiiSoft\Rbac\Manager\PhpManager;
use Lengbin\YiiSoft\Rbac\Manager\PhpManagerFile;
use Lengbin\YiiSoft\Rbac\RuleFactory\ClassNameRuleFactory;
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
        $item = ArrayHelper::getValue($config, 'item');
        $assignment = ArrayHelper::getValue($config, 'assignment');
        $rule = ArrayHelper::getValue($config, 'rule');
        $menu = ArrayHelper::getValue($config, 'menu');
        $logKey = ArrayHelper::getValue($config, 'log', 'default');
        $logger = $container->get(LoggerFactory::class)->get('Rbac', $logKey);

        if ($driver === DbManager::class) {
            $itemChild = ArrayHelper::getValue($config, 'itemChild');
            $connection = ArrayHelper::getValue($config, 'connection', Connection::class);
            $db = new $connection($container);
            $class = new $driver($ruleFactory, $db, $cache, $logger, $item, $itemChild, $assignment, $rule, $menu);
        }

        if ($driver === PhpManager::class) {
            $class = new $driver($ruleFactory, $cache, $logger, $item, $assignment, $rule, $menu);
        }

        if ($driver === PhpManagerFile::class) {
            $directory = ArrayHelper::getValue($config, 'directory');
            $class = new $driver($ruleFactory, $directory, $logger, $item, $assignment, $rule, $menu);
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
