<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/28
 * Time: 15:44
 */

namespace GoSwoole\Plugins\Scheduled\ExampleClass;

use GoSwoole\BaseServer\Plugins\Logger\GetLogger;

class TestScheduledTask
{
    use GetLogger;

    public function test()
    {
        $this->info("这是一次定时调用");
    }

    public function dynamic()
    {
        $this->info("这是一次dynamic定时调用");
    }
}