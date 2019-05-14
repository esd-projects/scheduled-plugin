<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 15:05
 */

namespace ESD\Plugins\Scheduled;


use ESD\BaseServer\Plugins\Logger\GetLogger;
use ESD\BaseServer\Server\Server;
use ESD\Plugins\Scheduled\Beans\ScheduledTask;
use ESD\Plugins\Scheduled\Event\ScheduledExecuteEvent;

class ScheduledTaskHandle
{
    use GetLogger;

    public function __construct()
    {
        //监听任务事件的执行
        goWithContext(function () {
            $channel = Server::$instance->getEventDispatcher()->listen(ScheduledExecuteEvent::ScheduledExecuteEvent);
            while (true) {
                $event = $channel->pop();
                if ($event instanceof ScheduledExecuteEvent) {
                    goWithContext(function () use ($event) {
                        $this->execute($event->getTask());
                    });
                }
            }
        });
    }

    /**
     * 执行调度
     * @param ScheduledTask $scheduledTask
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function execute(ScheduledTask $scheduledTask)
    {
        $className = $scheduledTask->getClassName();
        $taskInstance = Server::$instance->getContainer()->get($className);
        call_user_func([$taskInstance, $scheduledTask->getFunctionName()]);
        $this->debug("执行{$scheduledTask->getName()}任务");
    }
}