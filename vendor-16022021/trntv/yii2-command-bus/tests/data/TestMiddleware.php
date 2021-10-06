<?php

namespace trntv\bus\tests\data;

use trntv\bus\interfaces\Middleware;
use yii\base\BaseObject;

/**
 * Class TestMiddleware
 * @package trntv\bus\tests\data
 * @author Eugene Terentev <eugene@terentev.net>
 */
class TestMiddleware extends BaseObject implements Middleware
{

    public function execute($command, callable $next)
    {
        \Yii::info('middleware test 1 ok');
        $result = $next($command);
        \Yii::info('middleware test 2 ok');

        return $result;
    }
}
