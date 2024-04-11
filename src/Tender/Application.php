<?php

namespace Nebula\NebulaResponseTender\Tender;

use Nebula\NebulaResponse\Kernel\ServiceContainer;
use Nebula\NebulaResponseTender\Tender\Clients\DetailClient;
use Nebula\NebulaResponseTender\Tender\Clients\LoginClient;
use Nebula\NebulaResponseTender\Tender\Clients\SearchClient;
use Nebula\NebulaResponseTender\Tender\Providers\ClientsServiceProvider;
use Nebula\NebulaResponseTender\Tender\Providers\TestServiceProvider;

/**
 * Class Application
 *
 * @property Client $test
 * @property LoginClient $login 登录
 * @property SearchClient $search 查询列表
 * @property DetailClient $detail 详情
 */
class Application extends ServiceContainer
{

    protected $providers = [
        TestServiceProvider::class,
        ClientsServiceProvider::class
    ];

    /**
     * Handle dynamic calls.
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->base->$method(...$args);
    }
}
