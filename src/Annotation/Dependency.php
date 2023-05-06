<?php

declare(strict_types=1);

namespace HyperfHelper\Dependency\Annotation;


use Attribute;
use Hyperf\Di\Annotation\AnnotationInterface;
use HyperfHelper\Dependency\Annotation\Collector\DependencyCollector;
use ReflectionException;

#[Attribute(Attribute::TARGET_CLASS)]
class Dependency implements AnnotationInterface
{

    /**
     * @param string<class-string> $identifier 接口类
     * @param int $priority 权重
     */
    public function __construct(public string $identifier = '', protected int $priority = 1)
    {
    }

    /**
     * @throws ReflectionException
     */
    public function collectClass(string $className): void
    {
        DependencyCollector::collectorDependency($className, $this->identifier, $this->priority);
    }

    public function collectMethod(string $className, ?string $target): void
    {
    }

    public function collectProperty(string $className, ?string $target): void
    {
    }
}
