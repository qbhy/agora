<?php
/**
 * @link https://docs.agora.io/cn/Interactive%20Broadcast/dashboard_restful_live?platform=All_Platforms
 * User: qbhy
 * Date: 2019-01-22
 * Time: 22:06
 */

namespace Qbhy\Agora;

use GuzzleHttp\RequestOptions;
use Hanson\Foundation\AbstractAPI;

class Api extends AbstractAPI
{
    protected $app;

    const BASE_URL = 'https://api.agora.io/dev/v1';

    public function __construct(Agora $agora)
    {
        $this->app = $agora;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $params
     *
     * @return array
     */
    public function request(string $method, string $uri, array $params = [])
    {

        $type    = $method === 'GET' ? RequestOptions::QUERY : RequestOptions::JSON;
        $options = [
            $type                => $params,
            RequestOptions::AUTH => [$this->app->getConfig('id'), $this->app->getConfig('secret')]
        ];
        $result  = $this->getHttp()
                        ->request($method, Api::BASE_URL . $uri, $options)
                        ->getBody()
                        ->__toString();
        return json_decode($result, true);
    }


}