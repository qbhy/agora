<?php
/**
 * User: qbhy
 * Date: 2019-01-22
 * Time: 21:55
 */

namespace Qbhy\Agora;

use Hanson\Foundation\Foundation;

/**
 * Class Agora
 *
 * @property Project     $project
 * @property Usage       $usage
 * @property KickingRule $kicking_rule
 *
 * @package Qbhy\Agora
 */
class Agora extends Foundation
{
    protected $providers = [
        ServiceProvider::class,
    ];
}