<?php

namespace Nebula\NebulaResponseTender\Tender\Kernel;

use GuzzleHttp\RequestOptions;
use Nebula\NebulaResponse\Kernel\BaseClient as Client;
use Nebula\NebulaResponseTender\Tender\Kernel\Sign\Jwt;

class BaseClient extends Client
{
    protected $headers = [
        'Accept'          => 'application/json, text/plain, */*',
        'Accept-Language' => 'zh-CN,zh-Hans;q=0.9',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Content-Type'    => "application/json;charset=utf-8",
        'User-Agent'      => "Mozilla/5.0 (iPhone; CPU iPhone OS 17_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 mafengs-www-app/2.0.6",
    ];

    /**
     *
     * @param string $url
     * @param array $query
     * @return void
     */
    public function httpGet(string $url, array $query = [])
    {
        try {
            $res = $this->setHeaders($url, [], $query);
            unset($this->headers['Content-Type']);
            if ($res !== true) {
                return $res;
            }
            return $this->request($url, 'GET', ['query' => $query, RequestOptions::HEADERS => $this->headers]);
        } catch (\Nebula\NebulaResponse\Kernel\Exceptions\InvalidConfigException|\GuzzleHttp\Exception\GuzzleException $e) {
            return ['code' => 100, 'message' => $e->getMessage()];
        }
    }

    /**
     * post请求
     * @param string $url
     * @param array $data
     * @param array $query
     * @return array|\Nebula\NebulaResponse\Kernel\Contracts\Arrayable|\Nebula\NebulaResponse\Kernel\Http\Response|\Nebula\NebulaResponse\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        try {
            $res = $this->setHeaders($url, $data, $query);
            if ($res !== true) {
                return $res;
            }
            return $this->request($url, 'POST', ['query' => $query, 'json' => $data, RequestOptions::HEADERS => $this->headers]);
        } catch (\Nebula\NebulaResponse\Kernel\Exceptions\InvalidConfigException|\GuzzleHttp\Exception\GuzzleException $e) {
            return ['code' => 100, 'message' => $e->getMessage()];
        }
    }

    /**
     * 设置header
     * @param string $url
     * @param array $data
     * @param array $query
     * @return void
     */
    protected function setHeaders(string $url, array $data = [], array $query = [])
    {
        $token_key = 'tender-token';
        $token     = $this->app->cache->get($token_key);
        if (!$token) {
            $result = $this->app['login']->send();
            if ($result['code'] !== 0) {
                return $result;
            }
            $token = $result['data']['token'];
            list($header, $payload, $s) = explode('.', $token);

            $payload = json_decode(base64_decode($payload), true);
            $this->app->cache->set($token_key, $token, $payload['exp'] -time() - 10);
        }
        $sub  = Jwt::makeSub($url, $data, $query);
        $sgin = Jwt::makeSign($this->app->config->get('secret'), $sub, $this->app->config->get('expire', 10));

        $this->headers['X-sign']        = $sgin;
        $this->headers['Authorization'] = $token;
        return true;
    }
}
