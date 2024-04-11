<?php

namespace Nebula\NebulaResponseTender\Tender\Providers;

use Nebula\NebulaResponse\Kernel\ServiceContainer;
use Nebula\NebulaResponseTender\Tender\Clients\DetailClient;
use Nebula\NebulaResponseTender\Tender\Clients\LoginClient;
use Nebula\NebulaResponseTender\Tender\Clients\SearchClient;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ClientsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        !isset($pimple['login']) && $pimple['login'] = function ($pimple) {
            return new LoginClient($pimple);
        };
        !isset($pimple['search']) && $pimple['search'] = function ($pimple) {
            return new SearchClient($pimple);
        };
        !isset($pimple['detail']) && $pimple['detail'] = function ($pimple) {
            return new DetailClient($pimple);
        };
    }
}
