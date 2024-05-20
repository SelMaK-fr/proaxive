<?php

namespace Selmak\Proaxive2\Infrastructure\Twig;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigMessageExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @param ContainerInterface $container The container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getFunctions(): array
    {
        return [
            // @phpstan-ignore-next-line
            new TwigFunction('flash', [$this->container->get(TwigMessageRuntime::class), 'getMessages']),
            // @phpstan-ignore-next-line
            new TwigFunction('has_message', [$this->container->get(TwigMessageRuntime::class), 'hasMessage']),
            // @phpstan-ignore-next-line
            new TwigFunction('form_data', [$this->container->get(TwigMessageRuntime::class), 'formData']),
            // @phpstan-ignore-next-line
            new TwigFunction('errors', [$this->container->get(TwigMessageRuntime::class), 'errors']),
        ];
    }

}