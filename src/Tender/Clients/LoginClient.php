<?php

namespace Nebula\NebulaResponseTender\Tender\Clients;

use GuzzleHttp\RequestOptions;
use Nebula\NebulaResponseTender\Tender\Kernel\BaseClient;
use Nebula\NebulaResponseTender\Tender\Kernel\Sign\Jwt;

class LoginClient extends BaseClient
{

    protected $uri = '/user/login/verifyByPassword';

    public function send($data = [])
    {
        $data = [
            'account'  => $this->app->config->get('account'),
            'password' => $this->app->config->get('password')
        ];
        $sub  = Jwt::makeSub($this->uri, $data);
        $sgin = Jwt::makeSign($this->app->config->get('secret'), $sub, $this->app->config->get('expire', 10));

        $this->headers['X-sign'] = $sgin;
        return $this->request($this->uri, 'POST', ['json' => $data, RequestOptions::HEADERS => $this->headers]);
    }
}
