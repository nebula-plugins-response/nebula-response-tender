<?php

namespace Nebula\NebulaResponseTender\Tender\Clients;

use Nebula\NebulaResponseTender\Tender\Kernel\BaseClient;

class SearchClient extends BaseClient
{

    protected $url = '/purchase/search';

    public function send($data)
    {
        return $this->httpPostJson($this->url, $data);
    }
}
