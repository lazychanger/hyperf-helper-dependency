<?php

declare(strict_types=1);

namespace HyperfHelper\Dependency\Annotation\Collector;

use Hyperf\Di\MetadataCollector;
use ReflectionClass;
use ReflectionException;

class DependencyCollector extends MetadataCollector
{
    public static array $container = [];

    /**
     * @throws ReflectionException
     */
    public static function collectorDependency(string $definition, string $identifier = '', int $priority = 1): void
    {
        if ($priority < 1) {
            return;
        }

        if ($identifier === '') {
            $reflectionClass = new ReflectionClass($definition);
            $interfaces = $reflectionClass->getInterfaceNames();
            if (count($interfaces) === 0) {
                $identifier = $definition;
            } else {
                $identifier = $interfaces[0];
            }
        }

        self::setContainerDependencyItem($identifier, $definition, $priority);
    }

    public static function getDependencies(): array
    {
        if (empty(self::$container)) {
            return [];
        }
        $dependencies = [];
        foreach (self::$container['d'] as $interfaceName => $dependency) {
            $dependencies[$interfaceName] = $dependency['definition'];
        }
        return $dependencies;
    }

    public static function walk(callable $cb): void
    {
        if (empty(self::$container)) {
            return;
        }

        foreach (self::$container['d'] as $identifier => $definition) {
            $cb($identifier, $definition['definition']);
        }
    }

    protected static function setContainerDependencyItem(string $identifier, string $definition, int $priority): void
    {
        // 初始化结构
        [, $storePriority, $has] = self::getContainerDependencyItem($identifier);

        // 如果 priority = 1, 旧依赖的priority为 1;
        // has 为true，1 - 1 < 1 = true; 则不覆盖
        // has 为false，1 - 0 < 1 = false；贼覆盖
        if ($priority - $has < $storePriority) {
            return;
        }

        self::$container['d'][$identifier] = [
            'definition' => $definition,
            'priority' => $priority,
        ];
    }

    /**
     * @return array [string, int, bool]
     */
    protected static function getContainerDependencyItem(string $identifier): array
    {
        if (!isset(self::$container['d'])) {
            goto DEFAULT_RETURN;
        }

        if (!isset(self::$container['d'][$identifier])) {
            goto DEFAULT_RETURN;
        }

        return [
            self::$container['d'][$identifier]['definition'] ?? '',
            self::$container['d'][$identifier]['priority'] ?? 1,
            true,
        ];

        DEFAULT_RETURN:
        return ['', 1, false];
    }
}
