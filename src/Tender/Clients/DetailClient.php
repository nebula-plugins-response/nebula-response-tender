<?php

namespace Nebula\NebulaResponseTender\Tender\Clients;

use Nebula\NebulaResponseTender\Tender\Kernel\BaseClient;

class DetailClient extends BaseClient
{

    protected $uri = '/purchase/getDetail?isPC=false';

    public function send($data)
    {
        $data['isPC'] = false;
        return $this->httpGet($this->uri, $data);
    }

}
