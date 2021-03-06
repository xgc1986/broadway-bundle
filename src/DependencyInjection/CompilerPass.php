<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class CompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @param string           $definitionId
     */
    protected function assertContainerHasDefinition(ContainerBuilder $container, $definitionId)
    {
        if (!$container->hasDefinition($definitionId)) {
            throw new InvalidArgumentException(
                sprintf('Service id "%s" could not be found in container', $definitionId)
            );
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $definitionId
     * @param string           $interface
     */
    protected function assertDefinitionImplementsInterface(ContainerBuilder $container, $definitionId, $interface)
    {
        $this->assertContainerHasDefinition($container, $definitionId);

        $definition      = $container->getDefinition($definitionId);
        $definitionClass = $container->getParameterBag()->resolveValue($definition->getClass());

        $reflectionClass = new ReflectionClass($definitionClass);

        if (! $reflectionClass->implementsInterface($interface)) {
            throw new InvalidArgumentException(
                sprintf('Service "%s" must implement interface "%s".', $definitionClass, $interface)
            );
        }
    }
}
