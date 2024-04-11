<?php

namespace Nebula\NebulaResponseTender\Tender\Kernel\Sign;

class Jwt extends \Firebase\JWT\JWT
{
    protected static $header = [
        'alg' => "HS256",
        'typ' => 'JWT',
    ];

    /**
     * @param string $uri
     * @param array $data
     * @param array $query
     * @return string
     */
    public static function makeSub(string $uri, array $data = [], array $query = []): string
    {
        $queryString = '';
        if ($query) {
            $queryString = str_replace(['%3A', '%28', '%29'], [':', "(", ")"], http_build_query($query));
        }
        $body = "";
        if ($data) {
            $body = json_encode($data);
        }
        return md5($uri . $queryString . $body);
    }

    /**
     * @param string $secret
     * @param string $sub
     * @param int $expire
     * @return void
     */
    public static function makeSign(string $secret, string $sub, int $expire)
    {
        $time    = time();
        $payload = [
            'sub' => $sub,
            'nbf' => $time,
            'iat' => $time,
            'exp' => $time + $expire
        ];
        return static::encode($payload, $secret, 'HS256');
    }

    public static function encode(array $payload, $key, string $alg, string $keyId = null, array $head = null): string
    {
        $header = ['alg' => $alg, 'typ' => 'JWT'];
        if ($keyId !== null) {
            $header['kid'] = $keyId;
        }
        if (isset($head) && \is_array($head)) {
            $header = \array_merge($head, $header);
        }
        $segments      = [];
        $segments[]    = static::urlsafeB64Encode((string)static::jsonEncode($header));
        $segments[]    = static::urlsafeB64Encode((string)static::jsonEncode($payload));
        $signing_input = \implode('.', $segments);

        $signature  = static::sign($signing_input, $key, $alg);
        $segments[] = static::urlsafeB64Encode($signature);

        return \implode('.', $segments);
    }
}
