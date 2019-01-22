<?php
/**
 * User: qbhy
 * Date: 2019-01-22
 * Time: 22:32
 */

namespace Qbhy\Agora;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['project'] = function (Agora $agora) {
            return new Project($agora);
        };
    }

}