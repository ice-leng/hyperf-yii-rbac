<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Lengbin\Hyperf\YiiDb\Rbac;

use Lengbin\Auth\User\AccessCheckerInterface;
use Lengbin\YiiDb\Rbac\ManagerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ManagerInterface::class       => RbacFactory::class,
                AccessCheckerInterface::class => RbacFactory::class,
            ],
            'publish'      => [
                [
                    'id'          => 'rbac',
                    'description' => 'The config for rabc.',
                    'source'      => __DIR__ . '/../publish/rbac.php',
                    'destination' => BASE_PATH . '/config/autoload/rbac.php',
                ],
            ],
        ];
    }
}
