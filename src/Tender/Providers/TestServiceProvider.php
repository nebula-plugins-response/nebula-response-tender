<?php

namespace Nebula\NebulaResponseTender\Tender\Providers;

use Nebula\NebulaResponseTender\Tender\Test\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TestServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple): void
    {
        !isset($pimple['test']) && $pimple['test'] = function ($pimple) {
            return new Client($pimple);
        };
    }
}
