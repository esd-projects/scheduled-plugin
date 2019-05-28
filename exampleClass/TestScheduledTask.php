<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 15:44
 */

namespace ESD\Plugins\Scheduled\ExampleClass;

use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Plugins\AnnotationsScan\Annotation\Component;
use ESD\Plugins\Scheduled\Annotation\Scheduled;

/**
 * @Component()
 * Class TestScheduledTask
 * @package ESD\Plugins\Scheduled\ExampleClass
 */
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

    /**
     * @Scheduled(cron="* * * * * *")
     */
    public function ann()
    {
        $this->info("这是一次注解定时调用");
    }
}